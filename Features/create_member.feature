Feature: I have to create member
  In order to assign project to a team member
  As a manger
  I need to be able to create a member

Scenario: I create a member with a firstname and a lastname
	Given I am on "/members/create"
	When I fill in "Firstname" with "Alex"
	And I fill in "Lastname" with "Tom"
	And I press "Save"
	Then I should be on "members"
	And I should see "Alex"

Scenario: I failed during the member creation because I don't fill the lastname
	Given I am on "/members/create"
	When I fill in "Firstname" with "Alex"
	And I press "Save"
	Then I should have an error with the following message ""