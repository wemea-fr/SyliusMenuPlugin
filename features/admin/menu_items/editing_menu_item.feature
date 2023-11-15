@managing_menu_item
Feature: Editing menu item
  In order to edit a menu item
  As an administrator
  I can access to edit page and validate it

  Background:
    Given the store operates on a single channel in "United States"
    And the store is available in en_US
    And the store has one menu
    And this menu has one item with title "Default item"
    And this menu item redirect on "http://domain.com/my/link.html"

  @ui
  @integration_test
  @integration_test_menus_plugin
  Scenario: I access at edit page and I should see expected fields
    Given I am logged in as an administrator
    And I want to edit this menu item
    Then I should see "target", "priority" and "CSS Classes" fields
    And I should see "type" link field
    And I should see "product" and "taxon" link fields
    And I should see custom link input in "English (United States)"
    And I should see "title" and "description" fields in "English (United States)"

  @ui
  Scenario: I can edit item
    Given I am logged in as an administrator
    And I want to edit this menu item
    When I set "A new title" for title in "English (United States)"
    And I save my changes about this item
    Then I should be notified that it has been successfully edited
    And the item with title "A new title" appear on the grid

  @ui
  Scenario: It resolves the current link's type
    Given I am logged in as an administrator
    And I want to edit this menu item
    Then the link's type is translations
    And the value of custom link field is "http://domain.com/my/link.html" in "English (United States)"

  @ui @javascript
  Scenario: I can change type of the link
    Given the store has a product "useless product"
    And I am logged in as an administrator
    When I want to edit this menu item
    Then the link's type is translations
    When I select "Link to product" as link type
    And I set the product "useless product" as link
    And I save my changes about this item
    Then I should be notified that it has been successfully edited
    And the item with title "Default item" and type "product" appear on the grid
