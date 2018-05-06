#  A Service Layer & DDD for CakePHP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Build Status](https://img.shields.io/scrutinizer/build/g/burzum/cakephp-service-layer/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/burzum/cakephp-service-layer/)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/burzum/cakephp-service-layer/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/burzum/cakephp-service-layer/?branch=master)
[![Code Quality](https://img.shields.io/scrutinizer/g/burzum/cakephp-service-layer/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/burzum/cakephp-service-layer/?branch=master)

This is more a **design pattern** and **conceptual idea** than a lot of code and will *improve the maintainability* of your code base. This plugin just provides some classes to help you applying this concept in the CakePHP framework following the way of the framework of convention over configuration.

## Introduction

The rule of thumb in any MVC framework is basically "fat models, skinny controllers".

While this works pretty well the abstraction can be done even better by separating for example the DB operations from the actual [business logic](https://en.wikipedia.org/wiki/Business_logic). Most Cake developers probably use the table objects as a bucket for everything. This is, strictly speaking, not correct. A table object should just encapsulate whatever is in the direct concern of that table. Queries related to that table, custom finders and so on. Some of the principles we want to follow are [separation of concerns](https://en.wikipedia.org/wiki/Separation_of_concerns) and [single responsibility](https://en.wikipedia.org/wiki/Single_responsibility_principle). The `Model` folder in CakePHP represents the [Data Model](https://en.wikipedia.org/wiki/Data_model) and should not be used to add things outside of this conern to it. A service layer helps with that.

The service class, a custom made class, not part of the CakePHP framework, would implement the real business logic and do any kind of calculations or whatever else logic operations need to be done and pass the result back to the controller which would then pass that result to the view.

This ensures that each part of the code is easy to test and exchange. For example the service is as well usable in a shell app because it doesn't depend on the controller. If well separated you could, in theory, have a plugin with all your table objects and share it between two apps because the application logic, specific to each app, would be implemented in the service player *not* in the table objects.

[Martin Fowler's](https://en.wikipedia.org/wiki/Martin_Fowler) book "[Patterns of Enterprise Architecture](https://martinfowler.com/books/eaa.html)" states:

> The easier question to answer is probably when not to use it. You probably don't need a Service Layer if your application's business logic will only have one kind of client - say, a user interface - and it's use case responses don't involve multiple transactional resources. [...]
> 
> But as soon as you envision a second kind of client, or a second transactional resource in use case responses, it pays to design in a Service Layer from the beginning.

There is a simple paragraph [on this page](https://blog.fedecarg.com/2009/03/11/domain-driven-design-and-mvc-architectures/) that explains pretty well why DDD in MVC is a pretty abstract and very opinionated topic:

> According to Eric Evans, Domain-driven design (DDD) is not a technology or a methodology. It’s a different way of thinking about how to organize your applications and structure your code. This way of thinking complements very well the popular MVC architecture. The domain model provides a structural view of the system. Most of the time, applications don’t change, what changes is the domain. **MVC, however, doesn’t really tell you how your model should be structured. That’s why some frameworks don’t force you to use a specific model structure, instead, they let your model evolve as your knowledge and expertise grows.**

CakePHP doesn't feature a template structure of any DDD or service layer architecture for that reason. It's basically up to you. This plugin provides you *one possible* implementation. It's not carved in stone, nor do you have to agree with it. Consider this plugin as a suggestion or template for the implementation and as a guidance for developers who care about maintainable code but don't know how to further improve their code base yet.

## How to use it

CakePHP by default uses locators instead of a dependency injection container. This plugin gives you a CakePHP fashioned service locator and a trait so you can simply load services anywhere in your application by using the trait.

There is also a ServicePaginatorTrait that allows you to use pagination inside your services with repository objects like the table objects.

The following example uses a `SomeServiceNameService` class:
```php
use Burzum\Cake\Service\ServiceAwareTrait;

class AppController extends Controller
{
    use ServiceAwareTrait;
}

class FooController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadService('SomeServiceName');
    }

    /**
     * Get a list of something for the current logged in user
     */
    public function index()
    {
        $this->set('results', $this->SomeServiceName->listingForUser(
            $this->Auth->user('id')
            $this->request
        ));
    }
}
```

If there is already a property with the name of the service used in the controller a warning will be thrown. In an ideal case your controller won't have to use any table instances anyway when using services. The tables are not a concern of the controller.

## Why no DI container?

You could achieve the very same by using a DI container of your choice but there was never really a need to do so before, the locators work just fine as well and they're less bloat than adding a full DI container lib. There was no need to add a DI container to any CakePHP app in the past ~10 years for me, not even in big projects with 500+ tables. One of the core concepts of CakePHP is to go by conventions over wiring things together in a huge DI config or using a container all over the place that is in most cases anyway just used like a super global bucket by many developers.

This is of course a very opinionated topic, so if you disagree and want to go for a DI container, feel free to do so! It's awesome to have a choice!

DI plugins for CakePHP:

 * [Piping Bag](https://github.com/lorenzo/piping-bag)
 * [Pimple DI](https://github.com/rochamarcelo/cake-pimple-di)
 * [CakePHP DI Generic PSR 11 Adapter](https://github.com/robotusers/cakephp-di)
 
You might find more DI plugins in the [Awesome CakePHP list of plugins](https://github.com/FriendsOfCake/awesome-cakephp#dependency-injection).

## License

Copyright Florian Krämer

Licensed under The MIT License Redistributions of files must retain the above copyright notice.
