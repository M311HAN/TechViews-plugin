# TechViews Plugin for WordPress

The TechViews plugin is a powerful tool designed for WordPress sites to track and dynamically display the top-viewed blog posts in a section called "Hot Right Now". This plugin refreshes every hour, offering visitors real-time insights into trending content.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Displaying the Top Views](#displaying-the-top-views)
  - [Automatic Resets](#automatic-resets)
- [Additional Information](#additional-information)
- [Deactivation and Cleanup](#deactivation-and-cleanup)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Real-Time Tracking**: Tracks views on individual posts every hour without affecting their total view count.
- **Dynamic Display**: Automatically updates the "Hot Right Now" page with the top 10 viewed posts from the last hour while preserving lifetime views.
- **View Count Formatting**: Simplifies large numbers by converting them to a more readable format (e.g., 1K for 1000 views, 1M for 1 million views).
- **Shortcode Integration**: Use the `[techviews_hot_right_now]` shortcode to display the list of top posts anywhere on your site.
- **Cron Scheduling**: Ensures that hourly views are reset on schedule without manual intervention.

## Installation

1. Download the `techviews.php` file from the repository.
2. Upload it to your WordPress site's plugin directory.
3. Navigate to the WordPress admin panel and activate the TechViews plugin through the 'Plugins' menu.

## Usage

### Displaying the Top Views

Add the shortcode `[techviews_hot_right_now]` to any page or post where you want to display the list of "Hot Right Now" posts. This will render an ordered list of the top 10 posts based on the views they received in the last hour.

### Automatic Resets

The plugin automatically resets the hourly view counts for all posts every hour, ensuring that the data displayed is always fresh and relevant. This reset does not affect the total view counts of the posts.

## Additional Information

For a visual representation of how the plugin affects your site, visit the `screenshots` folder in this repository. It contains images showing the layout and functionality of the "Hot Right Now" feature as it appears on a live site.

## Deactivation and Cleanup

Upon deactivation, the plugin will cleanly remove all scheduled events associated with it, ensuring no residual performance impacts on your site.

## Contributing

Feel free to fork this repository and contribute back by submitting pull requests with improvements or additional features.

## License

This plugin is open-source and free to use under the [GPLv2 license](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

Enjoy using TechViews to enhance your WordPress site's engagement! Created by https://github.com/M311HAN
