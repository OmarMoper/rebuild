## Drush Rebuild

[Drush Rebuild]() is a Drush extension for rebuilding local development
environments. It is not meant to be used for deploying to production. Rebuild
staging environments at your own risk.

Drush Rebuild relies on Drush aliases. Typical Drush
aliases are extended by providing a path to a Git repository as well as a path
to local tasks per alias to be performed when rebuilding. Any additional
values can be defined in a `rebuild` array in your alias.

For example:

	<?php

	$aliases['dev'] = array(
	  'root' => '/path/to/site',
	  'path-aliases' => array(
	    '%git-repo' => '/path/to/git-repo',
  	  '%local-tasks' => '/path/to/local-tasks-dir',
	  ),
	  'rebuild' => array(
	  	'drupal_5_site_root' => '/path/to/drupal5/siteroot',
	  ),
	);

	?>

The local tasks subdir, typically in a `resources` directory in your git repo,
should contain a Drush script, `tasks.script`. This is where you define the actions
that occur when rebuilding your development environment.

Drush Rebuild doesn't make many assumptions about your development workflow
(i.e. Drush Make, entire codebase is in the Git repo, symlinks setup from a repo
to another directory, etc), nor does it care about extra steps you need to take
when configuring a development environment, like disabling caching, adjusting
connections with 3rd party services, and so on.

By default, drush rebuild will create a backup of your environment
by using Drush 5's archive-dump command.

## tasks.script

`example.tasks.script` contains some usage examples of local tasks. `tasks.script`
is a drush script written in PHP.

## Example usage

`drush rebuild @mysite.local`

