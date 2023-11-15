@managing_menu_item
Feature: Creating menu item
  In order to create one menu item
  As an administrator
  I can access to create form and validate it


  Background:
    Given the store operates on a single channel in "United States"
    And the store is available in en_US
    And the store has one menu

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I access at creation page and see each field
    Given I am logged in as an administrator
    And I want to create a new item for this menu
    Then I should see "target", "priority" and "CSS Classes" fields
    And I should see "type" link field
    And I should see "product" and "taxon" link fields
    And I should see custom link input in "English (United States)"
    And I should see icon image file input
    And I should see "title" and "description" fields in "English (United States)"

  @ui
  Scenario: I create new item with all field and custom link
    Given I am logged in as an administrator
    And I want to create a new item for this menu
    When I select "New tab" as target
    And I set 10 as priority
    And I set "link underline" for "CSS Classes"
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link
    And I set "my title" for title in "English (United States)"
    And I set "one description" for description in "English (United States)"
    And I add it
    Then I should be notified that it has been successfully created
    And the item with title "my title" appear on the grid


  @ui @javascript
  Scenario: I create new item with product link
    Given the store has a product "Extraordinary useless product"
    And I am logged in as an administrator
    And I want to create a new item for this menu
    And I select "Link to product" as link type
    And I set the product "Extraordinary useless product" as link
    And I set "Product Link" for title in "English (United States)"
    And I add it
    Then I should be notified that it has been successfully created
    And the item with title "Product Link" and type "product" appear on the grid


  @ui @javascript
  Scenario: I create new item with product link
    Given the store has "useless taxon" taxonomy
    And I am logged in as an administrator
    And I want to create a new item for this menu
    And I select "Link to taxonomy" as link type
    And I set the taxon "useless taxon" as link
    And I set "Taxon Link" for title in "English (United States)"
    And I add it
    Then I should be notified that it has been successfully created
    And the item with title "Taxon Link" and type "taxon" appear on the grid

  @ui @javascript
  Scenario: I create new item and only selected type is persisted
    Given the store has a product "Extraordinary useless product"
    And the store has "useless taxon" taxonomy

    And I am logged in as an administrator
    And I want to create a new item for this menu

#    Set all value
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link

    And I select "Link to product" as link type
    And I set the product "Extraordinary useless product" as link

    And I select "Link to taxonomy" as link type
    And I set the taxon "useless taxon" as link

    And I set "Taxon Link" for title in "English (United States)"
    And I add it

    Then I should be notified that it has been successfully created
#    It return taxon only if other property are null (so other are not persisted)
    And the item with title "Taxon Link" and type "taxon" appear on the grid

  @ui @javascript
  Scenario: Display the link input according to the type selector
    Given I am logged in as an administrator
    And I want to create a new item for this menu
    When I select "Custom link" as link type
    Then the "translations" link field is visible
    And the "product" and "taxon" link fields are hidden

    When I select "Link to product" as link type
    Then the "product" link field is visible
    And the "translations" and "taxon" link fields are hidden

    When I select "Link to taxonomy" as link type
    Then the "taxon" link field is visible
    And the "product" and "translations" link fields are hidden
