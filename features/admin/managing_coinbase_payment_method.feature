@managing_coinbase_payment_method
Feature: Adding a new coinbase payment method
    In order to pay for orders in different ways
    As an Administrator
    I want to add a new payment method to the registry

    Background:
        Given the store operates on a channel named "Web-USD" in "USD" currency
        And I am logged in as an administrator

    @ui
    Scenario: Adding a new coinbase payment method
        Given I want to create a new Coinbase payment method
        When I name it "Coinbase" in "English (United States)"
        And I specify its code as "coinbase_test"
        And I fill the API Key with "test"
        And I fill the Webhook Secret Key with "test"
        And make it available in channel "Web-USD"
        And I add it
        Then I should be notified that it has been successfully created
        And the payment method "Coinbase" should appear in the registry
