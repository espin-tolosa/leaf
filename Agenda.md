# Writing my own framework to make APIs

## Steps

1. Create a project with composer
2. Add **autoload**
3. Learning Stage:
	- rules of access of wider scoped objects in nested included pages
	- rules of resolution for path include and require
	- symfony http foundation object interface as a way to abstract PHP access to HTTP request and response data
	- run-time route resolution as a way to securily blind access to our filesystem leaving open just one entry point
	- how to configure the route where a SPA build in React or any other JS framework is looking for the assets it needs