# [MageVision](https://www.magevision.com/) Mini Cart Coupon Extension for Magento 2

## Overview
The Mini Cart Coupon extension gives the ability to your customers to apply a coupon code in Mini Cart. There is no need anymore to wait until the Cart or Checkout page. Additional you can configure to display the Grand Total and the Discount Amount in Mini Cart. Provide a better shopping experience to your customers and a better overview of the total cost of their purchases. 

## Key Features
    * Apply a coupon code in Mini Cart
    * Display Grand Total in Mini Cart
    * Display Discount Amount in Mini Cart
    * Ajax processing and totals calculation
    * Better customers’ shopping experience
    
	
## Other Features
	* Developed by a Magento Certified Developer
	* Meets Magento standard development practices
    * Single License is valid for 1 live Magento installation and unlimited test Magento installations
	* Simple installation
	* 100% open source

## Compatibility
Magento Community Edition 2.1 - 2.2 - 2.3

## Installing the Extension
	* Backup your web directory and store database
	* Download the extension
		1. Sign in to your account
		2. Navigate to menu My Account → My Downloads
		3. Find the extension and click to download it
	* Extract the downloaded ZIP file in a temporary directory
	* Upload the extracted folders and files of the extension to base (root) Magento directory. Do not replace the whole folders, but merge them.If you have downloaded the extension from Magento Marketplace, then create the following folder path app/code/MageVision/MiniCartCoupon and upload there the extracted folders and files.
        * Connect via SSH to your Magento server as, or switch to, the Magento file system owner and run the following commands from the (root) Magento directory:
              1. cd path_to_the_magento_root_directory 
              2. php bin/magento maintenance:enable
              3. php bin/magento module:enable MageVision_MiniCartCoupon
              4. php bin/magento setup:upgrade
              5. php bin/magento setup:di:compile
              6. php bin/magento setup:static-content:deploy
              7. php bin/magento maintenance:disable
        * Log out from Magento admin and log in again
		
		
## How to Use
Navigate to Magento Admin under Stores → Configuration → MageVision Extensions → Mini Cart Coupon to enable and configure the extension.

## Support
If you need support or have any questions directly related to a [MageVision](https://www.magevision.com/) extension, please contact us at [support@magevision.com](mailto:support@magevision.com)