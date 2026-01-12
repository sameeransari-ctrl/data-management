<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'password_set_success' => 'Password reset successfully.',
    'otp_verify_successfully' => 'OTP verified successfully.',
    'otp_send_success' => 'OTP send successfully',
    'password_set_success' => 'Password has been successfully updated.',
    'logout_success' => 'You have been logged out successfully.',
    'fsnDetails' => 'FSN details',
    'error' => [
        'account_not_verified' => 'Your account is not verified',
        'account_is_inactive' => 'Your account is not active',
        'invalid_login_details' => 'The email or mobile number entered is incorrect.',
        'invalid_admin_login_details' => 'The email or password entered is incorrect.',
        'something_went_wrong' => 'Something went wrong.',
        'user_detail_not_found' => 'User detail not found!',
        'link_expire' => 'Reset password Link Expired.',
        'session_expired' => 'Session expired or Invalid token.',
        'this_role_is_inactive' => 'This role is Inactive.',
        'phone_number_does_not_exist' => 'The phone number has not been registered.',
        'email_does_not_exist' => 'The email has not been registered.',
        'otp' => [
            'not_matched' => 'Invalid verification code',
            'expired' => 'OTP has been expired',
            'regenerate' => 'Please regenerate verification code',
            'wait_for_few_minutes' => 'Please Wait A Few Minutes Before You Try Again',
        ],
        'email' => [
            'not_found' => 'Email not found'
        ],
        'phoneNumberAlreadyTaken' => 'The phone number has already been taken',
        'emailAlreadyExists' => 'The email already exists',
        'internet_connection_error' => 'Slow internet correction.',
        'exception' => [
            'server' => 'Internal server error.',
            'maintenance' => 'Server is down for maintenance.',
            'page_not_found' => 'Page not found.',
            'user_not_found' => 'User not found.',
            'model_not_found' => 'Model not found.',
            'not_allowed' => 'You are not allowed to perform this action.',
            'product_not_found' => 'Product not found.',
            'service_temporarily_unavailable' => 'Service temporarily unavailable.',
            'too_many_attempts' => 'Too many attempts.',
        ],
        'page' => [
            'oops' => 'Oops! Why you’re here?',
            'we_are_sorry' => 'We are very sorry for inconvenience. It looks like you’re try to access',
            'we_are_sorry_a_page' => 'a page that either has been deleted or never existed',
            'back_to_home' => 'Back To Home',
            'not_access' => 'Sorry, You Are Not Allowed to Access This Page.',
        ],
        'product' => [
            'no_proudcts_found' => 'There is no products found',
            'minimum_two_products_required' => 'Minimum two products required for compare',
            'maximum_three_products_allowed' => 'Maximum three products allowed for compare',
            'rating_already_stored' => 'Rating already stored',
            'no_questions_found' => 'There is no questions found',
            'product_already_scanned' => 'Product already scanned'
        ],
        'product_answers_already_stored' => 'Product answers already stored',
        'already_exists' => 'Already exists',
        'fsn' => [
            'not_found' => 'FSN details not found',
        ],
        'excel' => [
            'missing_required_headings' => 'Missing required headings: :missingHeading',
        ],
    ],
    'success' => [
        'add_faq' => 'FAQs added successfully.',
        'update_faq' => 'FAQs updated successfully.',
        'faq_deleted' => 'FAQs deleted successfully.',
        'update_cms' => ':title updated successfully.',
        'update_status' => 'Status updated successfully.',
        'success' => 'Success',
        'cache_cleared' => 'Cache cleared successfully.',
        'email' => [
            'update' => 'Email update successfully.'
        ],
        'content_updated' => 'Content updated successfully',
        'password-change'  => 'Password updated successfully.',
        'user_activated' => 'User Activated',
        'added' => ':type added successfully.',
        'updated' => ':type updated successfully.',
        'deleted' => ':type deleted successfully.',
        'profile_updated' => 'Profile updated successfully.',
        'product_updated' => 'Product updated successfully.',
        'active' => 'Activated',
        'inactive' => 'inactivated',
        'rating_stored_success' => 'Product rating stored successfully',
        'file_uploaded' => 'We have uploaded the file we will notify you once completed.',
        'scan_product_deleted' => 'Product has been deleted successfully',
        'scan_product_removed' => 'Product has been removed successfully',
        'store_scan_product' => 'Product has been scanned successfully',
        'answer_stored_success' => 'Product answers stored successfully',
        'available' => 'Available',
        'settings_updated' => 'Settings updated successfully',
        'partially_imported' => 'Partially imported',
        'user_deleted' => 'User has been deleted successfully',
        'user_activity_updated' => 'User activity updated',
    ],
    'notification' => [
        'udiCreate' => [
            'title' => 'New basic UDI Created by staff.',
            'message' => 'The New basic UDI is created by staff successfully.'
        ],
        'clientCreate' => [
            'title' => 'New Client created by staff.',
            'message' => 'The New Client is created by staff successfully.'
        ],
        'fsnCreate' => [
            'title' => ':productName FSN received',
            'message' => 'New FSN received :productName'
        ],
        'productCreate' => [
            'title' => 'New Product Added.',
            'message' => 'A new Product has been added by :addedBy.'
        ],
        'roleCreate' => [
            'title' => 'New Role created by staff.',
            'message' => 'The New Role is created by staff successfully.'
        ],
        'staffCreate' => [
            'title' => 'New Staff created by staff.',
            'message' => 'The New Staff is created by staff successfully.'
        ],
        'userCreate' => [
            'title' => 'New User created by staff.',
            'message' => 'The New User is created by staff successfully.'
        ],
        'userRegistered' => [
            'title' => 'New User Registered.',
            'message' => 'New User has registered on UDI.'
        ],
        'clientRegistered' => [
            'title' => 'New Manufacture Registered.',
            'message' => 'New Manufacture has registered on UDI Plateform.'
        ],
        'ratingReview' => [
            'title' => 'Feedback Received on :productName and :udiNumber',
            'message' => 'Feedback received on the :productName/:udiNumber.'
        ],
        'productScanned' => [
            'title' => 'Product scanned by user.',
            'message' => 'The product :productName/:udiNumber scanned by the :userName.'
        ],
        'productAnswered' => [
            'title' => 'Answered on :productName/:udiNumber.',
            'message' => 'Answer received on the :productName/:udiNumber.'
        ],
        'productImportUdiExist' => [
            'title' => 'UDI numbers already exist.',
            'message' => "In your recent Product Import, some UDI number's product are already exist in the platform- :notificationMessage"
        ],
        'productImportActorIdRequired' => [
            'title' => 'Actor Id does not exist.',
            'message' => "In your recent Product Import, some Actor ID/SRN are not exist in the platform. Kindly add Client's for the mentioned Actor ID/SRN in the platform and reupload the file- :notificationActorIdMessage"
        ]
    ]
];
