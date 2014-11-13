Feature: Add User to chat
  In order to talk to others
  As a logged in user
  I need to be able to add a user to the chat

  @javascript
  Scenario: Add user to a chat
    Given I am logged in as "testuser1"
    And I press "New Chat"
    And I wait "2000" for AJAX to finish
    And I enter "shared" in "name" field
    And I press "Create Chat"
    And I wait "2000" for AJAX to finish
    And I follow "shared"
    And I wait "2000" for AJAX to finish
    When I press "Add User"
    And I wait "2000" for AJAX to finish
    And I select "testuser2" from "user"
    And I press "Invite User"
    And I wait "2000" for AJAX to finish
    Then I should see "testuser2" in the "#chatTabContent" element

  @javascript
  Scenario: Don't allow users to be invited more than once. (NOTE: This relies on the previous test which is normally bad form. I did this for simplicity but could create a new chat instead)
    Given I am logged in as "testuser1"
    And I wait "2000" for AJAX to finish
    And I follow "shared"
    And I wait "2000" for AJAX to finish
    When I press "Add User"
    And I wait "2000" for AJAX to finish
    And I select "testuser2" from "user"
    And I press "Invite User"
    And I wait "2000" for AJAX to finish
    Then I should see "This user is already subscribed to this chat." in the "#userModal" element