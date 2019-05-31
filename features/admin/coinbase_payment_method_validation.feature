@managing_coinbase_payment_method
Feature: Coinbase payment method validation
    In order to avoid making mistakes when managing a payment method
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given the store operates on a channel named "Web-RUB" in "RUB" currency
        And the store has a payment method "Offline" with a code "offline"
        And I am logged in as an administrator

    @ui
    Scenario: Trying to add a new coinbase payment method without specifying required configuration
        Given I want to create a new Coinbase payment method
        When I name it "Coinbase" in "English (United States)"
        And I add it
        Then I should be notified that "API key" fields cannot be blank
        And I should be notified that "Webhook Secret Key" fields cannot be blank
