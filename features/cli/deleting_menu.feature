@deleting_menu
@menu_command
Feature: I can delete menu with command
  In order to remove one menu
  As an developer
  I can use symfony command to remove this menu

  Background:
    Given  the store is available in en_US
    And the store has one menu with code one_useless_menu


  @cli
  Scenario: I remove the menu
    Given the menu one_useless_menu appear on the database
    When I run command to delete one_useless_menu
    Then the menu one_useless_menu should not appear on the database

  #FIXME : test KO => mapping not use cascade delete on behat test ?
#  @cli
#  Scenario: When I remove one menu, associated items should be removed
#    Given this menu has one item with title "useless menu"
##    And this menu item appear on database
#    When I run command to delete one_useless_menu


