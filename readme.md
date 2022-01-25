# AGORA.COMMUNITY

### VERSIONS

* PHP 7.4
* MYSQL 5.7


* sudo apt install docker-compose (on windows install docker)
* git clone https://github.com/cytopia/devilbox && cd devilbox
* cp env-example .env
* sudo docker-compose up // first time slow - downloading
* visit http://127.0.0.1 you should see devilbox homescreen
* cd devilbox/data/www && git clone -b animchallenge2022 git@bitbucket.org:eeighty/agora.community.git
* /etc/hosts add 127.0.0.1 agora.community.loc (ignore error if on virtualbox)
* cd devilbox && sudo ./shell.sh && cd agora.community
* composer install
* npm install
* mv .env.example to .env
* php artisan key:generate
* php artisan storage:link
* create database & import provided sql file (agoracommunity.sql.zip)
* npm run watch
* http://172.16.238.10:3000/ (IP will differ - now in live view - browser will auto-reload when detecting a change in a file) or visit http://agora.community.loc
* do not run the seeder, we imported the very last version of the database, here's an example for creating an admin:

```
$a = User::create([
	'first_name' => 'Super',
	'last_name' => 'Admin',
	'email' => 'admin@admin.com',
	'password' => 'secret',
	'confirmation_code' => md5(uniqid(mt_rand(), true)),
	'confirmed' => true
]);
$a->->assignRole(config('access.users.admin_role'));
```

## CONTACT

Never hesitate to contact me when you have a question, do not loose time, if I don't know the answer we can find it together or I will ask you to dig.


## DEVILBOX

If using devilbox, check php version in DevilBox, we are using 7.4
https://devilbox.readthedocs.io/en/latest/getting-started/change-container-versions.html
edit .env in devilbox folder and set php version in .env:
PHP_SERVER=7.4

## DATABASE

All the tables that begins with ac_ are custom (not included in laravel boiler plate).

ac_accounts belongsTo User via user_id: adding info for collaborators and experts - todo eventually polymorphic relation separating collaborator from experts
ac_account_languages: listing the language spoken by our experts
ac_account_tags: tags for experts or collaborators (not use for now)
ac_assets: file assets to download (polymorph of content)
ac_categories: categories in the platform for content (can be ordered using seq)
ac_category_contents: belongsToMany
ac_contents: metadata for assts, videos and reviews (polymorphic)
ac_content_tags: tag for content
ac_currencies: ORDER - currencies
ac_deliveries: ORDER - metadata for an order
ac_domains: for now only Animation (not well used in eloquent for now)
ac_hyvors: we are using a third-party to manage our comments, we implemented a callback to save the comments in our database
ac_languages: languages available (see ac_account_languages)
ac_orders: ORDER - status of an order
ac_order_items: ORDER - eventually an order could be for multiple items (a review, an asset)
ac_payments: ORDER - stripe metadata (or eventually paypal), free
ac_payment_type: ORDER - stripe, free for now
ac_prices: ORDER - all the pricing in all the currencies for all the products
ac_products: ORDER - pre-recorded / live & private / public & 15min / 30min
ac_products_families: ORDER - we are selling only reviews for now
ac_reviews reviews assets (video, syncsketch link etc)  (polymorph of content)
ac_statuses: ORDER the status of an order flow
ac_tags: all the tags
ac_terms: legal doc in https://agora.community/about
ac_units: ORDER - we currently sell time only (minutes)
ac_videos: library of videos (polymorph of content)
media: thumbnails for assets (using spatie medialibrary)

## CODE

We are using laravel boilerplate 6:
https://laravel-boilerplate.com/6.0/documentation.html

The models locations are:
app/Models (when creating a model via artisan be sure to update the namespace and mv the model in the directory)

Disclaimer: this platform is not the core business of our company, I coded that when I had the time and really cut corners here and there. I will eventually clean-up (or ask you) the codebase, but for now let us continue with the iteration mindset.

### HELPER

We do have helpers, mostly:
app/Helpers/Global/AcHelper = get all contributors and experts (called mentors in the code) and duration formatting

Some helpers coming from the boilerplate:
Timezone helper "ie: convertToLocal"

### CONFIG

Custom config for the platform located in config/ac.php

### MIGRATIONS

All the migration before 2020* are from the boilerplate solution.

### RESOURCES

Not perfect. Needs refactoring eventually. The assets folder get compiled by webpack.mix.js, some of the CSS and JS are directly in the folder htdocs/... check webpack.mix.js to know which one get compile.

### CSS

If you need to modify the CSS, almost everything is in:
htdocs/css/AC/style.css

I am using inline-styling, you should not ;)

When running npm run watch, the style.css will be available for live reload -> it goes to htdocs/css/all.css - you don't modify this file directly, use AC/style.css

### JAVASCRIPT

Read about CSS, a little bit the same. Custom script for AC is located in htdocs/js/AC/scripts.js, you play with this file and npm run dev / watch / prod will do his job and put it in htdocs/js/all.js

### BLADE

Like the controller, it's separated (back-end vs front-end). One important folder is frontend/AC, the rest is coming from boilerplate.

The main layout is:
frontend/layouts/app.blade.php
backend/layouts/app.blade.php

Navigation / menu is:
frontend/includes/nav.blade.php
backend/includes/sidebar.blade.php

Poke-me if you don't find something.

## JOBS

We are using a single job for now. Uploading a file to S3.









# ... REST IS MY DOCUMENTATION ABOUT LARAVEL - NOTHING (well maybe 1-2 things) TOO SPECIFIC ABOUT THE PROJECT...

### DOCUMENTATION
https://laravel.com/docs/6.x/queues

Generate a job:
php artisan make:job ProcessPodcast

	<?php
	namespace App\Jobs;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	use File;
	use Illuminate\Support\Facades\Storage;

	class UploadToS3 implements ShouldQueue
	{
	    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	    public $filename;
	    /**
	     * Create a new job instance.
	     *
	     * @return void
	     */
	    public function __construct($filename)
	    {
	        $this->filename = $filename;
	    }

	    /**
	     * Execute the job.
	     *
	     * @return void
	     */
	    public function handle()
	    {
	        $file = storage_path() . '/uploadReviews/' . $this->filename;
	        if (Storage::disk('s3')->put($this->filename, fopen($file, 'r+'))) {
	                File::delete($file);
	        }
	    }
	}

Include it in your controller:
use App\Jobs\UploadToS3;

Call it:
UploadToS3::dispatch($videoFile);

Using supervisor to run and monitor a job (restart it if failing):

	sudo apt-get install supervisor
	mkdir -p /var/log/laravel && touch /var/log/laravel/worker.log &&  nano /etc/supervisor/conf.d/laravel-worker.conf 

Config:

	[program:laravel-worker]
	process_name=%(program_name)s_%(process_num)02d
	command=php /srv/www/.../artisan queue:work database --tries=1
	autostart=true
	autorestart=true
	user=www-data
	numprocs=1
	redirect_stderr=true
	stdout_logfile=/var/log/laravel/worker.log

Start the process:

	sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start laravel-worker:*

Attention, if you **modify UploadToS3** you need to rerun step 3.


## FRONT-END ADDING A LIBRARY

	npm install --save-dev video.js
	in webpack.mix.js
	    .extract([
	        // Extract packages from node_modules to vendor.js
	        'jquery',
	        ...
	        'video.js'
	    ])

	app.js
	import videojs from 'video.js';
	window.videojs = videojs;

	app.scss
	// Video-js
	@import '~video.js/dist/video-js.css';

	blade
	var player = videojs('my-video');

## MIGRATION
https://laravel.com/docs/6.x/migrations#column-modifiers

Example:

	php artisan make:migration create_TABLENAME_table --create=NEWTABLE
	php artisan make:migration add_busted_email_alert_to_timesheet --table=timesheets

OR make the model and create the migration with it:

	php artisan make:model -m AcStat (singular)
**(don't forget to move the model in Models dir and add \Models in namespace)**

Examples:

	$table->string('WorkStatus');
	$table->float('PlannedHours', 8, 2);
	$table->string('email')->unique();  
	$table->string('avatar_location')->nullable();
	$table->unsignedTinyInteger('active')->default(1);
	$table->time('length')->nullable()->after('video');

	// Foreign key
	$table->foreign('mentor_id')->references('id')->on('ac_accounts');

	// INDEX
	$table->unique('slug');
	$table->primary(['ac_content_id', 'ac_category_id']); (pivot table)

	// POLYMORPH
	$table->nullableMorphs('accountable');

	//HASMANY
	hasMany pivot thing can be something like:
	Schema::create('ac_content_tags', function (Blueprint $table) {
	            $table->unsignedBigInteger('ac_tag_id');
	            $table->unsignedBigInteger('ac_content_id');
	            $table->timestamps();
        
            $table->foreign('ac_tag_id')
                ->references('id')
                ->on('ac_tags');

            $table->foreign('ac_content_id')
                ->references('id')
                ->on('ac_contents');

        
            $table->primary(['ac_tag_id', 'ac_content_id']);
        });
        

 You can add insert directly in the migration:
 
        DB::table('ac_currencies')->insert(
            [
                ['iso' => 'USD', 'currency' => 'United States dollar'],
                ['iso' => 'CAD', 'currency' => 'Canadian dollar'],
                ['iso' => 'EUR', 'currency' => 'EURO']
            ]
        );

Running the migration:
php artisan migrate

## MODEL

Make a model (normally with a migration see above):

	php artisan make:model Flwork (capital singular) (+ -m to create migration)
	mv it from App Model to App\Models (change namespace to App\Models;)

include it in your controller:

	use App\Models\Flwork;

## DATABASE

### Using Eloquent ORM
(https://laravel.com/docs/6.x/eloquent)
You can use all the available query builder function

	$flight = App\Flight::find(1);
	$flight = App\Flight::where('active', 1)->first();
	$flights = App\Flight::find([1, 2, 3]);
	$model = App\Flight::findOrFail(1);
	$model = App\Flight::where('legs', '>', 100)->firstOrFail();
	$count = App\Flight::where('active', 1)->count();
	$max = App\Flight::where('active', 1)->max('price');

### Using Query Builder (https://laravel.com/docs/6.x/queries)
Using DB::table examples:

	DB::table('users')->get();
	DB::table('users')->where('name', 'John')->first();
	DB::table('users')->count();
	DB::table('orders')->max('price');
	DB::table('orders')->where('finalized', 1)->avg('price');
	DB::table('orders')->where('finalized', 1)->exists();
	DB::table('orders')->where('finalized', 1)->doesntExist();
	DB::table('users')->select('name', 'email as user_email')->get();
	$users = DB::table('users')->where([
	    ['status', '=', '1'],
	    ['subscribed', '<>', '1'],
	])->get();
	$users = DB::table('users')
	                    ->where('votes', '>', 100)
	                    ->orWhere('name', 'John')
	                    ->get();
	DB::table('users')
	            ->where('name', '=', 'John')
	            ->where(function ($query) {
	                $query->where('votes', '>', 100)
	                      ->orWhere('title', '=', 'Admin');
	            })
	            ->get();
	$users = DB::table('users')
	                ->groupBy('account_id')
	                ->having('account_id', '>', 100)
	                ->get();
	DB::table('users')->insert(
	    ['email' => 'john@example.com', 'votes' => 0]
	);

### Direct query

	use Illuminate\Support\Facades\DB;
	DB::select("SELECT workOrderID, freelancerID, sum(hours) as sumHours, min(workDate) as firstTS, max(workDate) as lastTS FROM `timesheets` group by workOrderID, freelancerID")

## ELOQUENT RELATIONSHIP
https://laravel.com/docs/6.x/eloquent-relationships

### One To One

*User has a phone*
**User model**

	public function phone()
	    {
	        return $this->hasOne('App\Models\Phone');
	    }

**Phone model**

	public function user()
	    {
	        return $this->belongsTo('App\Models\User');
	    }

**Query**

	$phone = User::find(1)->phone;           


### One To Many

*Blog post may have an infinite number of comments*

**Post model**

	public function comments()
	    {
	        return $this->hasMany('App\Models\Comment');
	    }

**Comment model**

	public function post()
	    {
	        return $this->belongsTo('App\Models\Post');
	    }

**Query**

	$comment = App\Post::find(1)->comments()->where('title', 'foo')->first();
	$comment = App\Comment::find(1);
	echo $comment->post->title;


### Many To Many

*Many users may have the role of "Admin"*

To define this relationship, **three database tables are needed**: users, roles, and role_user. The role_user table is derived from the alphabetical order of the related model names, and contains the user_id and role_id columns

**Table structure:**

	users
	    id - integer
	    name - string

	roles
	    id - integer
	    name - string

	role_user
	    user_id - integer
	    role_id - integer

    
**User model**
	
	public function roles()
	{
	    return $this->belongsToMany('App\Models\Role');
	}
	$user = App\User::find(1);
	foreach ($user->roles as $role) {
	    //
	}

Chaining:

	$roles = App\User::find(1)->roles()->orderBy('name')->get();

**Role model**

*same as its User counterpart, with the exception of referencing the App\User model*

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

### Has One Through

If each supplier has one user, and each user is associated with one user history record, then the supplier model may access the user's history through the user

**Table structure:**

	users
	    id - integer
	    supplier_id - integer

	suppliers
	    id - integer

	history
	    id - integer
	    user_id - integer

TODO...

### Has Many Through

TODO...

### Polymorphic relationships
https://laravel.com/docs/6.x/eloquent-relationships#one-to-one-polymorphic-relations

It is possible to call a polymorph and get the belongTo from the child ie:

	$cotd->contentable->account->user->first_name

**Example model**

	class AcVideo extends Model
	{
	    protected $dates = ['releaseDate', 'created_at', 'updated_at' ];

	    public function content()
	    {
	        return $this->morphOne('App\Models\AcContent', 'contentable');
	    }

	    public function account()
	    {
	        return $this->belongsTo('App\Models\AcAccount', 'mentor_id');
	    }
	    
	    public function getMorphClass()
	    {
	        return 'MorphVideo';
	    }
	}

**Content**

	public function contentable()
	{
		return $this->morphTo();
	}

**Need also to list the morph in app.php aliases:**
	...
	'MorphReview' => App\Models\AcReview::class,
	'MorphAsset' => App\Models\AcAsset::class,
	'MorphVideo' => App\Models\AcVideo::class

**This is to avoid using full namespace of model inside the db**


### VALIDATION

In blade:

		@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
In controller:

        //validate the form
        $validatedData = $request->validate([
            'stripeToken' => 'required'
        ]);
        
Custom message:

	lang/en:
		'custom' => [
			'attribute-name' => [
				'rule-name' => 'custom-message',
			],
			'priceid' => [
				'required' => 'Ffff',
			]
		],


### ELOQUENT OPTIMIZATION
https://laravel.com/docs/6.x/eloquent-relationships#eager-loading
It is a must, you need to eager load, if not you will have many queries...
(::with when using eloquent & protected $with = ['mentor']; on model)

IE:

	Role::with(['users.account'])->mentors()->first();
	$categories = AcCategory::with('contents.contentable')->where('id', '!=', 1)->orderBy('seq')->get();
	class AcReview extends Model
	{
	    protected $dates = ['releaseDate', 'created_at', 'updated_at' ];
	    protected $with = ['mentor'];
	    
	    public function mentor()
	    {
	        return $this->belongsTo('App\Models\AcAccount', 'mentor_id');
	    }


## TASK SCHEDULING (CRON)
https://laravel.com/docs/6.x/scheduling

While developing you can login on devilbox and run:
php artisan schedule:run

app/Console/Kernel.php <- put you cron there (closure etc...)

All time are in UTC so monday ->weekly()->mondays()->at('13:00'); is 9:00 for Montreal...


## EMAIL
2 ways of sending email:

**1- For a quick mail without a class / view you can do:**

	$html = "<p>Username: ".auth()->user()->username."</p>";
	Mail::send(array(), array(), function ($message) use ($html) {
	    $message->to("patrick@agora.studio")
	    ->subject("hub.agora.studio - freelancerInfoGet KO")
	    ->from("info@agora.studio")
	    ->setBody($html, 'text/html');
	});

**2- For a real email (html + text - class & view)**

	php artisan make:mail NotifyUnsubmittedTimesheet

Create the view resources/views/frontend/mail (use a already blade and rename it) 
*(if you want to embed image use this: "{{ $message->embed(public_path() . '/img/agorastudio.png') }}")*

Modify the mailer class and change the render blade:

	return $this->view('frontend.mail.notification_unsubmitted_timesheet')
	        ->subject("Friendly reminder to submit your timesheet")
	        ->from("info@agora.studio", "Agora");
*if from is from the same as in the config/mail.php you can omit it*

Add the public variables needed for your blade & modify the constructor arguments:

    public $freelancerName;
    public $freelancerUsername;

    public function __construct($freelancerName, $freelancerUsername)
    {
        $this->freelancerName = $freelancerName;
        $this->freelancerUsername = $freelancerUsername;
    }

Finally send the email from your controller
Add the facade and use:

	use Illuminate\Support\Facades\Mail;
	use App\Mail\NotifyUnsubmittedTimesheet;

Send the email:

	Mail::to("patrick@agora.studio")->send(new NotifyUnsubmittedTimesheet("Name", "UserName"));

## MAINTENANCE

php artisan down --message="Upgrading Database" --allow=(yourip)
php artisan up

## ADDING A PAGE IN BO

1-
Add new controller

	php artisan make:controller Backend/Auth/Timesheet/TimesheetController

2-
routes/backend/auth.php
use new controller
IE:

	+    // Email Management
	+    Route::group(['namespace' => 'Email'], function () {
	+        Route::get('email', [EmailController::class, 'index'])->name('email.index');
	+    });

*Don't forget to include it (use ...)*

3-
Add the page in the BO menu
resources/views/backend/includes/sidebar.blade.php

	+            <li class="divider"></li>
	+            <li class="nav-item nav-dropdown {{
	+                active_class(Active::checkUriPattern('admin/email*'), 'open')
	+            }}">
	+                <a class="nav-link nav-dropdown-toggle {{
	+                    active_class(Active::checkUriPattern('admin/email*'))
	+                }}" href="#">
	+                    <i class="nav-icon fas fa-list"></i> Email
	+                </a>
	+                <ul class="nav-dropdown-items">
	+                    <li class="nav-item">
	+                        <a class="nav-link {{
	+                            (Active::checkUriPattern('admin/log-viewer'))
	+                        }}" href="{{ route('log-viewer::dashboard') }}">
	+                            @lang('menus.backend.log-viewer.dashboard')
	+                        </a>
	+                    </li>
	+                    <li class="nav-item">
	+                        <a class="nav-link {{
	+                            active_class(Active::checkUriPattern('admin/log-viewer/logs*'))
	+                        }}" href="{{ route('log-viewer::logs.list') }}">
	+                            @lang('menus.backend.log-viewer.logs')
	+                        </a>
	+                    </li>
	+                </ul>
	+            </li>


4- make the view

	resources/views/backend/auth/xxx/index.blade.php


## DATATABLE
https://yajrabox.com/docs/laravel-datatables/master

To use datatable jquery plugin with laravel:

	composer require yajra/laravel-datatables:^1.5
	php artisan vendor:publish --tag=datatables
	php artisan vendor:publish --tag=datatables-buttons
	php artisan vendor:publish --tag=datatables-html

Add resources/js/backend/app.js:

	require('datatables.net-bs4');
	require('datatables.net-buttons-bs4');

Add resources/sass/backend/app.scss

	@import "~datatables.net-bs4/css/dataTables.bootstrap4.css";
	@import "~datatables.net-buttons-bs4/css/buttons.bootstrap4.css";

npm run dev

php artisan datatables:make Orders (include Models)

	public function dataTable($query)
	    {
	        return datatables()
	            ->eloquent($query)
	            ->addColumn('action', '<a href="'.route('admin.library.assets.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
	            ->editColumn('thumb', '<img style="max-width: 120px;" src="https://cdn.agora.community/{{$thumb}}">')
	            ->rawColumns(['thumb', 'action']);
	    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return AcAsset::select(['ac_contents.title', 'ac_assets.*'])->leftJoin('ac_contents', function($q) {
            $q->on('ac_contents.contentable_id', '=', 'ac_assets.id');
            $q->where('ac_contents.contentable_type', '=', 'MorphAsset');
        })->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('orders-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->parameters([
                        "scrollY" =>        "600px",
                        "scrollCollapse" => true,
                        "paging" =>         false
                    ])
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden(),
            Column::make('thumb')->title('Thumbnail'),
            Column::make('title'),
            Column::make('created_at'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

In Controller:

	use App\DataTables\OrdersDataTable;

	class OrderController extends Controller
	{

	    public function test(OrdersDataTable $dataTable)
	    {
	        return $dataTable->render('backend.order.test');
	    }
   
   
 backend/app.blade:
 
	 <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>


view:

	@section('content')
	{{$dataTable->table()}}
	@endsection

	@push('after-scripts')
	    {{$dataTable->scripts()}}
	@endpush


For route (EDIT) - make the param optional

	Route::get('library/assets/edit/{id?}', [AssetController::class, 'edit'])->name('library.assets.edit');


## FORM
Using https://spatie.be/docs/laravel-html/v2/general-usage/html-builder

Bind a model to a form so that it's populating the value automagically:

	{{ html()->modelForm($user, 'PATCH', route('admin.auth.user.update', $user->id))->class('form-horizontal')->open() }}

	{{ html()->text('first_name')
	                                ->class('form-control')
	                                ->placeholder(__('validation.attributes.backend.access.users.first_name'))
	                                ->attribute('maxlength', 191)
	                                ->required() }}
                            
For a **select** do:

In Controller:

	->withDomain(AcDomain::get()->pluck('title','id')->toArray());

In View:

	{{ html()->select('domain_id', $domain)
	                                    ->class('form-control')
	                                    ->required()
	                                    ->autofocus() }}  
                                
## PERMISSION AND ROLES

You can use the **artisan command to create permission**:

	php artisan permission:create-permission "license tools" web

Idem for roles but you need to assign also permissions to roles. You can use **seeder** too:

	// Create Roles
	$admin = Role::create(['name' => config('access.users.admin_role')]);
	$supervisor = Role::create(['name' => 'supervisor']);
	$lead = Role::create(['name' => 'lead']);
	$accountant = Role::create(['name' => 'accountant']);
	$freelancer = Role::create(['name' => 'freelancer']);
	$candidate = Role::create(['name' => 'candidate']);

	// Create Permissions

	$permissions = ['view backend', 'nextcloud create project', 'nextcloud invite client', 'nextcloud invite freelancer', 'nextcloud assign freelancer', 'send email to all freelancers', 'send email to assigned freelancers', 'user management', 'view wiki', 'view agora wiki lead', 'view timesheet'];

	foreach ($permissions as $permission) {
	    Permission::create(['name' => $permission]);
	}

	// ALWAYS GIVE ADMIN ROLE ALL PERMISSIONS
	$admin->givePermissionTo(Permission::all());

	// Assign Permissions to other Roles
	$supervisor->givePermissionTo(['view backend', 'nextcloud create project', 'nextcloud invite client', 'nextcloud invite freelancer', 'nextcloud assign freelancer', 'view wiki', 'view timesheet']);
	$lead->givePermissionTo(['view wiki', 'view agora wiki lead', 'view timesheet']);
	$accountant->givePermissionTo(['view backend', 'view wiki', 'view timesheet']);
	$freelancer->givePermissionTo(['view wiki', 'view timesheet']);


## CHECK PERMISSION

	@if(auth()->user()->hasRole('mentor'))
	@if(auth()->user()->can('view backend'))
                        

## JWT

composer install
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

## CARBON

Add to **models** the fields that are ... dates

	protected $dates = ['releaseDate', 'created_at', 'updated_at' ];

Then in blade view you can:

	$cotd->contentable->length->format('Y');


## HELPERS

Text excerpts (words or charac (limit)

	{{ \Illuminate\Support\Str::words($cotd->description, 50,'...') }}
	{{ \Illuminate\Support\Str::limit($cotd->description, 50,'...') }}


## MEDIALIBRARY (SPATIE)

https://spatie.be/docs/laravel-medialibrary/v8/requirements

	sudo apt install exif
	sudo apt install ffmpeg
	sudo apt install imagemagick
	sudo apt install jpegoptim optipng pngquant gifsicle

	composer require "spatie/laravel-medialibrary:^8.0.0"
	php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
	php artisan migrate
	php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"


## ADDING A SINGLE CSS JS
Let's say one page needs a javascript and css files. To add it just put it in:
resources/assets/js and css

open webpack.mix and add it:

	.styles('resources/assets/css/summernote-bs4.min.css', 'public/css/sm.min.css')
	.styles('resources/assets/css/dropzone.min.css', 'public/css/dz.min.css')
	.scripts('resources/assets/js/dropzone.min.js', 'public/js/dz.min.js')
	.scripts('resources/assets/js/summernote-bs4.min.js', 'public/js/sm.min.js')

use it with mix_cdn: 

	@push('after-styles')
		{{ style(mix_cdn('css/dropzone.min.css')) }}
	@endpush

	@push('after-scripts')
		{!! script(mix_cdn('js/dropzone.min.js')) !!}
		<script>
	        $(document).ready(function() {
	            
	        });
	    </script>
	@endpush


## CACHE
https://laravel.com/docs/6.x/cache

We are using a frontend composer for managing the cache for the dropdown menu in Library (search engine - by categories and tags).

Example:

	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\DB;

	/**
	 * Class GlobalComposer.
	 */
	class NavComposer
	{
	    /**
	     * Bind data to the view.
	     *
	     * @param View $view
	     */
	    public function compose(View $view)
	    {
	        $categories = Cache::remember('navSearchCategories', 21600, function () {
	            return AcCategory::with('contents.contentable')->where('id', '!=', 1)->orderBy('title')->get();
	        });

	        // the idea is to not select the tags that are categories - order by number of videos limit to 20
	        $tags = Cache::remember('navSearchTags', 21600, function () use ($categories) {
	            return DB::select("select ac_tags.title , count(ac_tag_id) as nbvid from ac_content_tags
	            left join ac_tags on ac_tags.id = ac_content_tags.ac_tag_id
	            where upper(ac_tags.title) not in('".strtoupper(implode('\', \'', $categories->pluck('title')->toArray()))."')
	            group by ac_content_tags.ac_tag_id, ac_tags.title
	            ORDER BY `nbvid` DESC LIMIT 12");
	        });

	        $view->with(['categories' => $categories, 'tags' => $tags, 'alltags' => $alltags]);
	    }
	}

## ASANA
https://developers.asana.com/docs

Go to developer console and create a personnal token: https://app.asana.com/0/developer-console

	composer install asana/asana

In controller add:

	use Asana\Client;

	$asana_client = Client::accessToken(env('ASANA_PERSONAL_ACCESS_TOKEN'));
	$projects = $asana_client->get('/projects', []);

Get custom fields GID:

	curl -X GET https://app.asana.com/api/1.0/workspaces/119206340616338/custom_fields   -H 'Accept: application/json'   -H 'Authorization: Bearer 1/1194761265258904:1dc95abb558d35565984f1ba734'

	$client = Client::accessToken(env('ASANA_PERSONAL_ACCESS_TOKEN'));
	$users = $client->users->getUsersForWorkspace(1197340616338, null, array('iterator_type' => false, 'page_size' => null))->data;


## FFMPEG
https://github.com/protonemedia/laravel-ffmpeg

	composer require pbmedia/laravel-ffmpeg
	php artisan vendor:publish --provider="ProtoneMedia\LaravelFFMpeg\Support\ServiceProvider"

configure filesystems.php disk (config) - for thumbnails and videos...

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'uploads' => [
            'driver' => 'local',
            'root' => storage_path('uploads'),
        ],

use FFMpeg;

	$fvid = FFMpeg::fromDisk('uploads')->open($videoFile);
	// create thumbnail
	//$t = FFMpeg::openUrl('https://cdn.agora.community/reviews/RaviGovind/PaulEliscupides/Eliscupides1.mp4')->getFrameFromSeconds(10)->export()->toDisk('thumnails')->save('FrameAt10sec.png');
	// get dimensions
	//$d = $fvid->getVideoStream()->getDimensions();

	// get fps
	$framerate = $fvid->getVideoStream()->get('r_frame_rate');
	$fps = eval('return '.$framerate.';');


## GOOD PRACTISE - TIPS & TRICKS

**1- Never use env('', 'default') in your controller or blade, always use a config** 

	config('app.something')

**2- Let's say you are using hasMany for the future, but really in reality you have only 1 = 1, so you can use (latest) in your model)**

    public function orderitems()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'order_id');
    }
    
VS (latest and singular)

    public function orderitem()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'order_id')->latest();
    }

**3- To make it easy to know what type of user profile we are dealing with, we can extend the User model with a couple of accessors**
*Attention function is important (get///Attribute)*

	public function getFullNameAttribute()
	    {
	    return "{$this->contentable->account->user->first_name} {$this->contentable->account->user->last_name}";
	    }
	 {{ $lastcontent->FullName }}  
	 
	  public function getHasAdminProfileAttribute()
	  {
	    return $this->profile_type == 'App\AdminProfile';
	  }
	  public function getHasCustomerProfileAttribute()
	  {
	    return $this->profile_type == 'App\CustomerProfile';
	  }

User::find(2)->hasAdminProfile
=> false
User::find(2)->hasCustomerProfile
=> true

**4- Add this to your model (watchout for security)**

	protected $guarded = [];  

**5- Use updateOrCreate when possible**

	AcDelivery::updateOrCreate(['order_id' => $order->id], [ 
	            'order_id' => $order->id,
	        ]);

**6- LoginController override trait (add this method):**

    protected function credentials(Request $request)
    {
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $field => $request->username,
            'password' => $request->password,
        ];
        return $credentials;   
        //return $request->only($this->username(), 'password');
    }

# REMEMBER never use asset-cdn sync <---------- BAD will delete all S3 bucket!!!