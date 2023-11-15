@managing_menu_item
Feature: I can remove item from menu
  In order to manage menu's items
  As an administrator
  I can remove item from a menu

  Background:
    Given the store is available in en_US
    And the store has one menu
    And this menu has one item with title "First item"
    And this menu has another item with title "Item to remove"


  @ui
  Scenario: I remove one item
    Given I am logged in as an administrator
    And I want to manage items of this menu
    Then I should see 2 items
    When I press button to remove "Item to remove"
    Then I should see 1 item
    And the item with title "First item" appear on the grid
    And the item with title "Item to remove" should not appear on the grid

  @ui
  Scenario: Image are moved when I delete item
    Given the "Item to remove" item has "troll.jpg" as icon
    And I am logged in as an administrator
    And I want to manage items of this menu
    Then I should see 2 items
    When I press button to remove "Item to remove"
    Then I should see 1 item
    And the item with title "First item" appear on the grid
    And the item with title "Item to remove" should not appear on the grid
    And last menu item icon is removed

