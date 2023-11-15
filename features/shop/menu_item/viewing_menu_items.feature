@viewing_menu_items
Feature: The menu contains items and display it
  I order to view custom menu
  As a visitor
  I should menu items
#TODO: add another feature to test translation

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "useless product"
    And the store has "useless taxon" taxonomy

    And the store has one menu with code homepage_footer_menu
    And this menu is enabled on this channel

    And this menu has one item with title "Custom link"
    And this menu item redirect on "https://www.wemea.fr"

    And this menu has another item with title "Product link"
    And this menu item redirect on the product "useless product"

    And this menu has another item with title "Taxon link"
    And this menu item redirect on the taxon "useless taxon"


  @ui
  Scenario: I should see each items
    Given I go to the homepage
    Then the menu homepage_footer_menu has 3 items
    And I should see "Custom link" item
    And I should see "Product link" item
    And I should see "Taxon link" item

  @ui
  Scenario: I should see image icons
    Given the "Custom link" item has "troll.jpg" as icon
    When I go to the homepage
    Then I should see "Custom link" item with "troll.jpg" icon

  @ui
  Scenario: Items have the correct target
    Given the "Custom link" item has _blank as target
    And the "Product link" item has _self as target
    When I go to the homepage
    Then the "Custom link" item should open link in new tab
    And the "Product link" item should open link in same tab

  @ui
  Scenario: Items are ordered by priority
    Given the "Custom link" item has not priority
    And the "Product link" item has 20 as priority
    And the "Taxon link" item has -5 as priority
    When I go to the homepage
    Then I should see "Taxon link" item at the 1st position
    And I should see "Product link" item at the 2nd position
    And I should see "Custom link" item at the 3rd position

