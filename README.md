### PHP Gitlab API

This is a php client for the [gitlab][1] API. This client bases on Guzzle 5 with service descriptions.
You can have a quick overview of the implemented gitlab APIs in the yml files in `lib/Client/ServiceDescription`.

**Work in progress**

I am currently working on implementing the remaining APIs as well as wrapping the responses in appropriate self
documenting entities. Furthermore I think I will create a facade around the currently used guzzle client to get rid of 
the untyped array of parameters which you currently have to pass for the guzzle service description.
Until I am content with the changes there will not be a stable version of this client.

If you still want to use the API as it is right now have a look at the tests and or the examples folder:

### Implemented APIs
 * Commits
 * Issues
 * Merge Requests

### Not implemented APIs (yet)
 * deploy_key_multiple_projects API
 * deploy_keys API
 * groups API
 * labels API
 * milestones API
 * notes API
 * oauth2 API
 * project_snippets API
 * projects API
 * repositories API
 * repository_files API
 * services API
 * session API
 * system_hooks API
 * users API

[1]: https://about.gitlab.com/
[2]: https://github.com/gitlabhq/gitlabhq/tree/master/doc/api
