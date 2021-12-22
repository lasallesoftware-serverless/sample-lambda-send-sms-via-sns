<?php

/**
 * This file is part of the Lasalle Software Serverless send SMS via SNS sample PHP Lambda function.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2021-2022 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://phpserverlessproject.com
 * @link       https://packagist.org/packages/lasallesoftware-serverless/sample-lambda-send-sms-via-sns
 * @link       https://github.com/lasallesoftware-serverless/sample-lambda-send-sms-via-sns
 *
 */

require './../vendor/autoload.php';


// Set the vars
$region = 'us-east-1';   // same region that you specified in the serverless.yml
$version = '2010-03-31';
$message = 'Message from your sample PHP Lambda function sending sms via sns';
$telephoneNumber = '15555555555';   // Your recipient's telephone number


// SNS client, using the AWS SDK for PHP
if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

    // For Lambda
    $SnsClient = new \Aws\Sns\SnsClient([
        'region'  => $region,
        'version' => $version,
    ]);

} else {

    // For your local development
    $SnsClient = new \Aws\Sns\SnsClient([
        'profile' => 'default',   // Change this name if your local profile name is different
        'region'  => $region,
        'version' => $version,
    ]);
}


// Set the SMS Attributes
try {
    $result = $SnsClient->SetSMSAttributes([
        'attributes' => [
            'DefaultSMSType' => 'Transactional',   // also "Promotional" (https://docs.aws.amazon.com/sns/latest/dg/sms_preferences.html)
        ],
    ]);
    var_dump($result);
    
} catch (AwsException $e) {

    if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

        // for Lambda, log the error message
        error_log($e->getMessage());

    } else {

        // for your local development, output error message
        var_dump($e->getMessage());
    }
}


// Send SMS
try {
    $result = $SnsClient->publish([
        'Message'     => $message,
        'PhoneNumber' => $telephoneNumber,
    ]);
    var_dump($result);

} catch (\Aws\Exception\AwsException $e) {

    if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

        // for Lambda, log the error message
        error_log($e->getMessage());

    } else {

        // for your local development, output error message
        var_dump($e->getMessage());
    }
}