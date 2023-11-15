@managing_menu_item
Feature: It resolve and display the correct resource
  In order to manage menu item
  As an administrator
  I should see the resource link according the selected type

  Background:
    Given the store is available in en_US
    And the store has one menu
    And this menu has one item with title "Default item"

  @ui @javascript
  Scenario: It resolves the current link from custom type
    Given this menu item redirect on "http://domain.com/my/link.html"
    And I am logged in as an administrator
    When I want to edit this menu item
    Then the link's type is translations
    And the value of custom link field is "http://domain.com/my/link.html" in "English (United States)"
    And the value of taxon link field is null
    And the value of product link field is null
    And the "translations" link field is visible
    And the "product" and "taxon" link fields are hidden


  @ui @javascript
  Scenario: It resolves the current link from product type
    Given the store has a product "The most useless product"
    And this menu item redirect on the product "The most useless product"
    And I am logged in as an administrator
    When I want to edit this menu item
    Then the link's type is product
    # The field contain the product's code
    And the value of product link field is "THE_MOST_USELESS_PRODUCT"
    And the value of taxon link field is null
    And the value of custom link field is null in "English (United States)"
    And the "product" link field is visible
    And the "translations" and "taxon" link fields are hidden

  @ui @javascript
  Scenario: It resolves the current link from taxon type
    Given the store has "useless products" taxonomy
    And this menu item redirect on the taxon "useless products"
    And I am logged in as an administrator
    When I want to edit this menu item
    Then the link's type is taxon
    # The field contain the taxon's code
    And the value of taxon link field is "useless_products"
    And the value of product link field is null
    And the value of custom link field is null in "English (United States)"

    And the "taxon" link field is visible
    And the "translations" and "product" link fields are hidden
