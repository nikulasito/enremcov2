# Fix Member Dashboard Loan Terms Display Issue

Status: ✅ Plan approved - implementing

## Completed Steps

- [x] Analyzed all relevant files (view, controllers, models, routes)
- [x] Confirmed code correctly queries/transforms/displays $loan->terms ?? 'N/A'
- [x] User confirmed: Active loans visible, 'N/A' shown; DB has terms data (likely member-specific NULL)

## Pending Steps

1. ~~Read key files~~ (done)
2. ~~Create plan~~ (done)
3. **Add debug logging** in MemberLoanController@index() to log memberKeys, query results, terms values
4. **Update view** to make 'N/A' more informative ('Terms not set - contact admin')
5. **Enhance controller** to derive terms if null (e.g. from dates)
6. **Test** & verify
7. **Data fix** (admin populate or migration)
8. Complete task

Next step: Edit MemberLoanController.php with logging + derivation logic
