@using_menu_items
@following_menu_item_link
Feature: I can use menu
  In order to navigate on the website
  As a visitor
  I can follow link and I should be redirect on the correct page

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "useless product"
    And the store has "useless taxon" taxonomy

    And the store has one menu with code homepage_footer_menu
    And this menu is enabled on this channel

  @ui
  Scenario: I follow product link
    Given this menu has one item with title "Useless Product"
    And this menu item redirect on the product "useless product"

    When I go to the homepage
    And I follow the "Useless Product" link
    Then I should be on "useless product" product detailed page

  @ui
  Scenario: I follow taxon link
    Given this menu has one item with title "Useless Taxon"
    And this menu item redirect on the taxon "useless taxon"

    When I go to the homepage
    And I follow the "Useless Taxon" link
    Then I should be on "useless taxon" taxon page

  @ui
  Scenario: The custom link redirect opn the correct page
    Given this menu has one item with title "Wemea website"
    And this menu item redirect on "https://wwww.wemea.fr"

    When I go to the homepage
    Then I should see "Wemea website" item
    And "Wemea website" item should redirect on "https://wwww.wemea.fr"
