<h1>Yii2 Template App Skeleton</h1>

<p><strong>Yii2 Template App Skeleton</strong> - A template for quickly starting a project with the Yii2 framework.</p>

<h2>Description</h2>

<p>
    Yii2 Template is a template that provides a skeleton for creating new applications using the Yii2 framework. It includes a minimal set of products required for PHP development, making the process of creating and extending applications easier.
</p>

<h2>Features</h2>

<ul>
    <li>Includes a ready-to-use technology stack for quickly deploying projects with Docker. This stack includes the following components:</li>
<ul>
    <li>Docker</li>
    <li>MongoDB</li>
    <li>Nginx</li>
    <li>PHP</li>
    <li>PostgreSQL</li>
    <li>Redis</li>
    <li>Yii2</li>  
</ul>
    <li>Easy creation of new applications such as web-app, api-app, console-app, which are ready to work without additional configurations.</li>
    <li>New method for configuring Yii2 applications and caching the configuration array for improved performance.</li>
    <li>Easy integration of new components and modules with the ability to extend the core Yii2 component or module.</li>
</ul>

<h2>Requirements</h2>

<p>To use the Yii2 Template, you need to have the following components installed on your server:</p>

<ul>
    <li>Docker</li>
    <li>Docker Compose</li>
</ul>

<h2>Installation and Setup</h2>
<ol>
    <li>Clone the repository to your local machine.</li>
    <li>Run Docker Compose to deploy the technology stack.</li>
</ol>


DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```