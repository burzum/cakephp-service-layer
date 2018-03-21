#  A Service Layer for CakePHP

This is more a design pattern and conceptual idea than a lot of code and will improve the maintainability of your code base. This plugin just provides some classes to help you using it.

The rule of thumb in any MVC framework is basically "fat models, skinny controllers".

While this works pretty well the abstraction can be done even better by separating for example the DB operations from the actual business logic. Most Cake developers probably use the table objects as a bucket for everything. This is, strictly speaking, not correct. A table object should just encapsulate whatever is in the direct concern of that table. Queries related to that table, custom finders and so on.

The service class, a custom made class, not part of the CakePHP framework, would implement the real business logic and do any kind of calculations or whatever else logic operations need to be done and pass the result back to the controller which would then pass that result to the view.

This ensures that each part of the code is easy to test and exchange. For example the service is as well usable in a shell app because it doesn't depend on the controller. If well separated you could, in theory, have a plugin with all your table objects and share it between two apps because the application logic, specific to each app, would be implemented in the service player *not* in the table objects.

## License

Copyright Florian Krämer

Licensed under The MIT License Redistributions of files must retain the above copyright notice.
