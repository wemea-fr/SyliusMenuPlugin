@managing_menu_item
Feature: I can add, change and remove icon of menu item
  In Order to manage store's menus
  As an administrator
  I can upload, change and remove icon for menu's items

#  FIXME: remove uploaded image after scenario
  Background:
    Given the store is available in en_US
    And the store has one menu

  @ui
  Scenario: I add image to new item
    Given I am logged in as an administrator
    And I want to create a new item for this menu
    And I select "Custom link" as link type
    And I set "http://domain.com/my/link.html" for custom link
    And I set "new item" for title in "English (United States)"
    And I attach "troll.jpg" image as icon
    When I add it
    Then I should be notified that it has been successfully created
    And "troll.jpg" is associated to this menu item as icon


  @ui
  Scenario: I can change icon of item
    Given this menu has one item
    And this menu item has "troll.jpg" as icon
    And I am logged in as an administrator
    When I want to edit this menu item
    And I attach "ford.jpg" image as icon
    And I save my changes about this item
    Then I should be notified that it has been successfully edited
    And I want to edit this menu item
    And "ford.jpg" is associated to this menu item as icon
    And last menu item icon is removed

  @ui
  Scenario: I can remove image
    Given this menu has one item
    And this menu item has "troll.jpg" as icon
    And I am logged in as an administrator
    When I want to edit this menu item
    And I remove the image
    And I save my changes about this item
    Then I should be notified that it has been successfully edited
    And this menu item has not image as icon
    And last menu item icon is removed

  @ui
  Scenario: I user abort to add image, image is not saved
    Given this menu has one item
    And I am logged in as an administrator
    And I want to edit this menu item
    And I am logged in as an administrator
    When I attach "troll.jpg" image as icon
    But I remove this image
    And I save my changes about this item
    Then this menu item has not image as icon

  @ui @javascript
  Scenario: Image and button are displaying according to state during edition
    Given this menu has one item
    And I am logged in as an administrator
    And I want to edit this menu item
    Then remove image button and image preview are hidden

    When I attach "troll.jpg" image as icon
    Then remove image button and image preview are visible

# FIXME : chrome drive crash on this step
  # Probably caused by "src/Resources/public/js/remove-item-image.js:29" :

#    When I save my changes about this item
#    And I want to edit this menu item
#    Then remove image button and image preview are visible
#    When I remove the image
#    Then remove image button and image preview are hidden

  @ui @javascript
  Scenario: Image and button are displaying according to state during creation
    Given I am logged in as an administrator
    And I want to create a new item for this menu
    Then remove image button and image preview are hidden

    When I attach "troll.jpg" image as icon
    Then remove image button and image preview are visible

#FIXME : chrome drive crash on this step
#    When I remove the image
#    Then remove image button and image preview are hidden
