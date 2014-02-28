# feature/createMember.feature
Feature: Create a Member
  In order to assign some project to a team member
  As a manager user
  I need to be able to create a team member

  Scenario: Searching for a page that does exist
    Given I am on "/members/create"
    When I fill in "Firstname" with "Joris"
    And I press "Save"
    Then I should fail