<?php
/**
 * Plugin Name: Product Link Scraper
 * Description: Simple plugin to scrape product links from any page
 * Version: 1.0
 * Author: Your Name
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu
add_action('admin_menu', 'pls_add_admin_menu');

function pls_add_admin_menu() {
    add_menu_page(
        'Product Link Scraper',
        'Link Scraper',
        'manage_options',
        'product-link-scraper',
        'pls_admin_page',
        'dashicons-admin-links',
        30
    );
}

// Admin page HTML
function pls_admin_page() {
    ?>
    <div class="wrap">
        <h1>Product Link Scraper</h1>
        <div style="max-width: 800px;">
            <form method="post" action="">
                <?php wp_nonce_field('pls_scrape_action', 'pls_scrape_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="page_url">Enter Page URL:</label>
                        </th>
                        <td>
                            <input type="url" 
                                   name="page_url" 
                                   id="page_url" 
                                   class="regular-text" 
                                   placeholder="https://example.com/shop" 
                                   required
                                   style="width: 100%;">
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" 
                           name="scrape_links" 
                           id="submit" 
                           class="button button-primary" 
                           value="Scrape Links">
                </p>
            </form>

            <?php
            // Process form submission
            if (isset($_POST['scrape_links']) && check_admin_referer('pls_scrape_action', 'pls_scrape_nonce')) {
                $url = esc_url_raw($_POST['page_url']);
                $links = pls_scrape_product_links($url);
                
                if (!empty($links)) {
                    echo '<div style="margin-top: 20px;">';
                    echo '<h2>Product Links (' . count($links) . ' found)</h2>';
                    echo '<textarea readonly style="width: 100%; height: 400px; font-family: monospace; padding: 10px;">';
                    echo esc_textarea(implode("\n", $links));
                    echo '</textarea>';
                    echo '<button type="button" class="button button-secondary" onclick="copyLinks()" style="margin-top: 10px;">Copy All Links</button>';
                    echo '</div>';
                    
                    // JavaScript for copy functionality
                    ?>
                    <script>
                    function copyLinks() {
                        var textarea = document.querySelector('textarea');
                        textarea.select();
                        document.execCommand('copy');
                        alert('Links copied to clipboard!');
                    }
                    </script>
                    <?php
                } else {
                    echo '<div class="notice notice-error" style="margin-top: 20px;"><p>No product links found or error fetching the page.</p></div>';
                }
            }
            ?>
        </div>
    </div>
    <?php
}

// Function to scrape product links
function pls_scrape_product_links($url) {
    $links = array();
    
    // Fetch the page content
    $response = wp_remote_get($url, array(
        'timeout' => 30,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ));
    
    if (is_wp_error($response)) {
        return $links;
    }
    
    $html = wp_remote_retrieve_body($response);
    
    if (empty($html)) {
        return $links;
    }
    
    // Load HTML into DOMDocument
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    libxml_clear_errors();
    
    // Find all links with class 'woocommerce-LoopProduct-link'
    $xpath = new DOMXPath($dom);
    $productLinks = $xpath->query("//a[contains(@class, 'woocommerce-LoopProduct-link')]");
    
    foreach ($productLinks as $link) {
        $href = $link->getAttribute('href');
        if (!empty($href) && !in_array($href, $links)) {
            $links[] = $href;
        }
    }
    
    // If no WooCommerce links found, try generic product links
    if (empty($links)) {
        $productLinks = $xpath->query("//li[contains(@class, 'product')]//a[@href]");
        foreach ($productLinks as $link) {
            $href = $link->getAttribute('href');
            if (!empty($href) && !in_array($href, $links)) {
                $links[] = $href;
            }
        }
    }
    
    return array_unique($links);
}