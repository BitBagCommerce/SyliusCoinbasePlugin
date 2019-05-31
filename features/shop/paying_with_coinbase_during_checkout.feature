@paying_with_coinbase_for_order
Feature: Paying with Coinbase during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with Coinbase

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "john@bitbag.pl" identified by "password123"
        And the store has a payment method "Coinbase" with a code "coinbase" and Coinbase payment gateway
        And the store has a product "PHP T-Shirt" priced at "€100.00"
        And the store ships everywhere for free
        And I am logged in as "john@bitbag.pl"

    @ui
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Coinbase" payment method
        When I confirm my order with Coinbase payment
        And I sign in to Coinbase and pay successfully
        Then I should be notified that my payment has been completed

    @ui
    Scenario: Cancelling the payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Coinbase" payment method
        When I confirm my order with Coinbase payment
        And I cancel my Coinbase payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again

    @ui
    Scenario: Retrying the payment with success
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Coinbase" payment method
        And I have confirmed my order with Coinbase payment
        But I have cancelled Coinbase payment
        When I try to pay again Coinbase payment
        And I sign in to Coinbase and pay successfully
        Then I should be notified that my payment has been completed
        And I should see the thank you page

    @ui
    Scenario: Retrying the payment and failing
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Coinbase" payment method
        And I have confirmed my order with Coinbase payment
        But I have failed Coinbase payment
        When I try to pay again Coinbase payment
        And I cancel my Coinbase payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again
