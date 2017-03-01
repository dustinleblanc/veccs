Feature: Co-Chair Can Manage Domains

  as a Co-Chair,
  I can manage Domains (topic areas),
  So that researchers can organize around topic areas and evidence can be aggregated by topic area.

  @api
  Scenario:
    Given I am logged in as a user with the "co_chair" role
    Then I am on "/admin/structure/domain/add"
    Then I fill in "edit-name-0-value" with "Domain 1"
    Then I fill in "edit-description-0-value" with "Donec id elit non mi porta gravida at eget metus."
    Then I submit the form with id "#edit-submit"
    Then I should see the text "Created the Domain 1 domain."

  @api
  Scenario:
    Given I am an anonymous user
    Then I am on "/admin/structure/domain/add"
    Then I should see "Access Denied"
