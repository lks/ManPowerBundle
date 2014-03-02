Feature: I have to create member
  In order to assign project to a team member
  As a manger
  I need to be able to create a member

Scenario: I create a member with a firstname and a lastname
	Given I am on "/members/create"
  	When I fill in "Firstname" with "Alex"
	And I fill in "Lastname" with "Tom"
	And I press "Save"
	Then I should be on "/members"
	And I should see "Alex"

Scenario: I want to create a member but I don't fill the lastname field which is mandatory
	Given I am on "/members/create"
  	When I fill in "Firstname" with "Alex"
	And I press "Save"
	Then I should have an error message

Scenario: I want to create a member but I don't fill the firstname field which is mandatory
	Given I am on "/members/create"
  	When I fill in "Lastname" with "Alex"
	And I press "Save"
	Then I should have an error message

Scenario: I want to create a member with an existing Firstname and Lastname couple
	Given I am on "/members/create"
	And It exist a member with the firstname and lastname : "Alex" and "Tom"
  	When I fill in "Firstname" with "Alex"
	And I fill in "Lastname" with "Tom"
	And I press "Save"
	Then I should have an error message