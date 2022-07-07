<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        $this->crudPolicies();
        $this->engagementPolicies();
        $this->kpiPolicies();
        $this->generalRPolicies();
        $this->visPolicies();
        $this->incentivePolicies();

        $this->okrPolicies();
        $this->addSessionPolicies();
        $this->editSessionPolicies();
        $this->deleteSessionPolicies();
        $this->addObjPolicies();
        $this->assignokrPolicies();

        $this->otherhrPolicies();
        $this->projectsPolicies();
        $this->manageProjPolicies();
        $this->rolemanagePolicies();
        $this->addStampPolicies();
        $this->addSignaturePolicies();
        $this->employeesPolicies();
        $this->teamsPolicies();

        $this->fillkpiprojectPolicies();
        $this->fillengageprojectPolicies();
        $this->fillkpiteamPolicies();
        $this->newrole();

        //

    }
 public function newrole()
    {
        Gate::define('newrole', function ($user) {
            return $user->hasAccess(['newrole']);
        });
    }
    public function fillkpiprojectPolicies()
    {
        Gate::define('fillkpiproject', function ($user) {
            return $user->hasAccess(['fillkpiproject']);
        });
    }
    public function fillengageprojectPolicies()
    {
        Gate::define('fillengageproject', function ($user) {
            return $user->hasAccess(['fillengageproject']);
        });
    }
    public function fillkpiteamPolicies()
    {
        Gate::define('fillkpiteam', function ($user) {
            return $user->hasAccess(['fillkpiteam']);
        });
    }
    public function fillengageteamPolicies()
    {
        Gate::define('fillengageteam', function ($user) {
            return $user->hasAccess(['fillengageteam']);
        });
    }
    public function crudPolicies()
    {


        Gate::define('crud', function ($user) {
            return $user->hasAccess(['crud']);
        });
    }
     public function incentivePolicies()
    {


        Gate::define('incentive', function ($user) {
            return $user->hasAccess(['incentive']);
        });
    }
    public function engagementPolicies()
    {
        Gate::define('engagement', function ($user) {
            return $user->hasAccess(['engagement']);
        });
    }
    public function kpiPolicies()
    {
        Gate::define('kpimodule', function ($user) {
            return $user->hasAccess(['kpimodule']);
        });
    }
    public function generalRPolicies()
    {
        Gate::define('generalreports', function ($user) {
            return $user->hasAccess(['generalreports']);
        });
    }
    public function visPolicies()
    {
        Gate::define('visualization', function ($user) {
            return $user->hasAccess(['visualization']);
        });
    }
    public function projectsPolicies()
    {
        Gate::define('projects', function ($user) {
            return $user->hasAccess(['projects']);
        });
    }
    public function manageProjPolicies()
    {
        Gate::define('manageprojects', function ($user) {
            return $user->hasAccess(['manageprojects']);
        });
    }
    public function otherhrPolicies()
    {
        Gate::define('otherhr', function ($user) {
            return $user->hasAccess(['otherhr']);
        });
    }

    public function rolemanagePolicies()
    {
        Gate::define('rolemanage', function ($user) {
            return $user->hasAccess(['rolemanage']);
        });
    }
    public function addStampPolicies()
    {
        Gate::define('addStamp', function ($user) {
            return $user->hasAccess(['addStamp']);
        });
    }
    public function addSignaturePolicies()
    {
        Gate::define('addSignature', function ($user) {
            return $user->hasAccess(['addSignature']);
        });
    }
    public function employeesPolicies()
    {
        Gate::define('employees', function ($user) {
            return $user->hasAccess(['employees']);
        });
    }
    public function teamsPolicies()
    {
        Gate::define('teams', function ($user) {
            return $user->hasAccess(['teams']);
        });
    }
    public function okrPolicies()
    {
        Gate::define('okr', function ($user) {
            return $user->hasAccess(['okr']);
        });
    }
    public function addSessionPolicies()
    {
        Gate::define('addSession', function ($user) {
            return $user->hasAccess(['addSession']);
        });
    }
    public function editSessionPolicies()
    {
        Gate::define('editSession', function ($user) {
            return $user->hasAccess(['editSession']);
        });
    }
    public function deleteSessionPolicies()
    {
        Gate::define('deleteSession', function ($user) {
            return $user->hasAccess(['deleteSession']);
        });
    }
    public function addObjPolicies()
    {
        Gate::define('addObjective', function ($user) {
            return $user->hasAccess(['addObjective']);
        });
    }
    public function assignokrPolicies()
    {
        Gate::define('assignokr', function ($user) {
            return $user->hasAccess(['assignokr']);
        });
    }
}
