# D8 Austin Product Link Scraper - WordPress Plugin

A WordPress plugin that scrapes and displays all product links from the D8 Austin shop page (https://www.d8austin.com/shop).

## Features

✅ **Admin Dashboard Interface** - Easy-to-use admin panel to scrape products
✅ **Product Information Extraction** - Extracts title, price, URL, sale status, and categories
✅ **CSV Export** - Download all product data as CSV file
✅ **Frontend Shortcode** - Display products on any page/post
✅ **AJAX-Powered** - Fast, seamless scraping without page reload
✅ **Responsive Design** - Works perfectly on all devices

## Installation

### Method 1: Upload via WordPress Admin
1. Download the `d8austin-product-scraper.php` file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin"
4. Choose the downloaded file
5. Click "Install Now"
6. Activate the plugin

### Method 2: FTP Upload
1. Upload `d8austin-product-scraper.php` to `/wp-content/plugins/` directory
2. Go to WordPress Admin → Plugins
3. Find "D8 Austin Product Link Scraper" and click "Activate"

### Method 3: Create Plugin Folder
1. Create a folder: `/wp-content/plugins/d8austin-scraper/`
2. Upload `d8austin-product-scraper.php` into this folder
3. Activate from WordPress Admin → Plugins

## Usage

### Admin Panel

1. **Access the Plugin**
   - Go to WordPress Admin
   - Click "D8 Products" in the sidebar menu

2. **Scrape Products**
   - Click the "Scrape Products" button
   - Wait for the scraping process to complete
   - View all products in a formatted table

3. **Export to CSV**
   - After scraping, click "Export to CSV" button
   - CSV file will download automatically
   - Contains: Number, Title, Price, URL, Sale Status, Categories

### Frontend Display (Shortcode)

Display products on any page or post using the shortcode:

**Basic Usage:**
```
[d8austin_products]
```

**With Options:**
```
[d8austin_products limit="10" show_price="yes" show_link="yes"]
```

**Shortcode Parameters:**
- `limit` - Number of products to display (default: -1 for all)
- `show_price` - Show/hide prices ("yes" or "no", default: "yes")
- `show_link` - Show/hide product links ("yes" or "no", default: "yes")

**Examples:**

Show only 12 products:
```
[d8austin_products limit="12"]
```

Show products without prices:
```
[d8austin_products show_price="no"]
```

Show 6 products with prices but no links:
```
[d8austin_products limit="6" show_link="no"]
```

## Product Data Extracted

For each product, the plugin extracts:

- **Product Title** - Full product name
- **Price** - Current price (including sale prices)
- **Product URL** - Direct link to product page
- **Sale Status** - Whether the product is on sale
- **Categories** - Product categories/tags

## CSV Export Format

The exported CSV file contains the following columns:

| No. | Product Title | Price | URL | On Sale | Categories |
|-----|---------------|-------|-----|---------|------------|
| 1   | Product Name  | $24.99| URL | Yes     | category-1, category-2 |

## Technical Details

### Requirements
- WordPress 5.0 or higher
- PHP 7.0 or higher
- PHP DOMDocument extension (usually enabled by default)
- `allow_url_fopen` enabled

### Performance
- Uses WordPress transient caching (1 hour) for shortcode
- AJAX-powered admin interface for better UX
- Efficient HTML parsing with DOMDocument and XPath

### Security
- Nonce verification for all AJAX requests
- Capability checks (only administrators can scrape)
- Sanitized and escaped output
- No direct file access allowed

## Troubleshooting

### Products not appearing?
- Check if the website structure has changed
- Ensure your server can access external URLs
- Check PHP error logs for any issues

### CSV export not working?
- Make sure you've scraped products first
- Check browser console for JavaScript errors
- Ensure you have proper file download permissions

### Shortcode not displaying products?
- Clear WordPress transient cache
- Try scraping products again from admin panel
- Check if shortcode is properly closed: `[d8austin_products]`

## Customization

### Styling the Frontend Display

Add custom CSS to your theme:

```css
.d8austin-products-list {
    /* Grid layout customization */
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.d8austin-product-item {
    /* Product card styling */
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.d8austin-product-item:hover {
    transform: translateY(-5px);
}
```

### Changing Cache Duration

Edit line in the plugin (search for `HOUR_IN_SECONDS`):

```php
set_transient('d8austin_products_cache', $products, DAY_IN_SECONDS); // Cache for 1 day
```

## Support

For issues or feature requests, please contact the plugin developer.

## Changelog

### Version 1.0.0
- Initial release
- Admin panel with scraping functionality
- CSV export feature
- Frontend shortcode
- Caching system

## License

GPL v2 or later

## Credits

Developed for D8 Austin product management.