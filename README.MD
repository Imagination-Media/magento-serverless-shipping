<p align="center">
<img alt="Magento logo" src="https://upload.wikimedia.org/wikipedia/en/thumb/5/53/Magento.svg/1280px-Magento.svg.png" width="224px"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<h2 align="center">
  Serverless Shipping Rates
</h2>

<h4 align="center"><b>This Magento module will extend the <a href="https://github.com/Imagination-Media/magento-serverless">Magento 2 serverless framework module</a> allowing us to use a serverless function to calculate the shipping rates for a Magento cart.</b></h4>

<br/>

<p align="center"><img src="https://img.shields.io/badge/Language-PHP-blue" />&nbsp;<img src="https://img.shields.io/badge/Ecommerce-Magento-orange"/>&nbsp;<img src="https://img.shields.io/badge/Hosting%20Provider-Google%20Cloud-blue" />&nbsp;<img src="https://img.shields.io/badge/Created%20By-IMDigital-orange" /></p>

<br/>

# Installation
## Requirements
- Magento >= 2.4.5-p1
- PHP >= 8.1

<br/>

## Installing the module and enabling it

To install this module we must run the composer require command requesting its installation:

```ssh
composer require imdigital/magento-serverless-shipping
```

<br/>

Then, enable it by modifying the app/etc/config.php or running the CLI command:

```ssh
php bin/magento module:enable ImDigital_ServerlessShipping
```

<br/>
<br/>

# Enabling delivery method
Once this module is installed, a new delivery method will be available on the Magento admin panel. It's called "Serverless Shipping Rates".

So, first, we need to add a serverless function to the serverless_functions database table, connecting Magento with the serverless function that's going to be used to calculate the shipping rates.

Then, on this delivery method, we can define a title for this delivery method and choose what's the serverless function that's going to return the shipping rates.

<p align="center">
<img src="https://github.com/Imagination-Media/magento-serverless-shipping/blob/master/screenshots/admin-config.png" alt="Delivery method configuration" />
<br/>
<i>Delivery method in the admin panel</i>
</p>

<br/>

The ***region_id*** is the region where the cloud function is hosted. The ***serviceaccount_key*** field is a [service account that we must create](https://cloud.google.com/iam/docs/service-account-overview), and then [generate a JSON service account key](https://cloud.google.com/iam/docs/keys-create-delete) with access to execute the functions.

These are the permissions you must add to your service account in order to be able to execute cloud functions:

<br/>
<br/>

# Returning the shipping rates
