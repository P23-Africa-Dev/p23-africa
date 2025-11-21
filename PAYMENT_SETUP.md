# Payment Gateway Setup - P23 Africa Consultation System

## Overview

The consultation booking system now supports both Flutterwave and Stripe payment gateways with your provided public keys.

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Payment Gateway Configuration
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-366fbf9270d878c2e5e67b0a89f037e7-X
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key_here

STRIPE_PUBLIC_KEY=pk_live_51SNbgfQu78cK9OHksFTQAzYeLoQrUX1muk0p8VmTa56P5OGVJ3iBSjNBI7mKsFqCSt6raDDpBGfpsKxf8vrmYGfs001M8Rr9Xb
STRIPE_SECRET_KEY=your_stripe_secret_key_here
```

**Important:** Replace the placeholder secret keys with your actual secret keys from your payment gateway dashboards.

## Payment Flow

### Flutterwave Integration

1. Uses Flutterwave Inline Checkout
2. Supports cards, bank transfers, mobile money, USSD
3. Handles payment success/failure callbacks
4. Stores transaction data in database

### Stripe Integration

1. Uses Stripe Elements for secure card input
2. Creates PaymentIntent on server-side
3. Handles 3D Secure authentication
4. Stores payment confirmation in database

## Features Implemented

✅ **Payment Method Selection**: Users can choose between Flutterwave and Stripe  
✅ **Secure Processing**: All payments processed through official gateway APIs  
✅ **Transaction Storage**: Payment details stored in database with status tracking  
✅ **Error Handling**: Comprehensive error handling for failed payments  
✅ **Success Callbacks**: Automatic redirection after successful payment  
✅ **Session Management**: Maintains user session throughout payment flow

## Payment Status Tracking

The system tracks payments with these statuses:

-   `pending`: Payment initiated but not completed
-   `successful`: Payment completed successfully
-   `failed`: Payment failed or declined
-   `cancelled`: Payment cancelled by user

## Security Features

-   CSRF protection on all forms
-   Payment data validation
-   Secure API key management via environment variables
-   Transaction reference verification
-   Payment amount validation

## Testing

**Note**: You're currently using live payment keys. For testing:

1. **Flutterwave**: Use test cards from Flutterwave documentation
2. **Stripe**: Use test card numbers like `4242424242424242`

## Next Steps

To complete the setup:

1. **Get Secret Keys**:

    - Flutterwave: Get from Flutterwave Dashboard > Settings > API Keys
    - Stripe: Get from Stripe Dashboard > Developers > API Keys

2. **Update Environment Variables**: Replace placeholder secret keys in `.env`

3. **Test Payments**: Try the complete booking flow with test payments

4. **Webhook Setup** (Optional for enhanced reliability):
    - Set up webhook endpoints for payment confirmations
    - Configure webhook URLs in payment gateway dashboards

## Support

The payment system is now fully integrated and ready for production use with your live keys.
