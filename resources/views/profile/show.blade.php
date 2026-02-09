<!-- resources/views/profile/show.blade.php -->

<x-app-layout>
<div class="details-container">


<div id="meminfonbar-wrapper" class="row">
    <div class="col-md-12">
        <!-- Desktop Navbar -->
        <ul class="meminfonav nav nav-pills d-none d-md-flex flex-column flex-md-row mb-4">
            <li class="nav-item"><a class="nav-link active" data-mod="89" data-modparent="81" href="#" onclick="gotoPage(this, &quot;/member/auth/navi/module/eeinfo/memDetails/60/tree&quot;, &quot;GET&quot;)"><i class="ico-memdets me-1"></i> Member Details</a></li>
            <li class="nav-item">
                <a class="nav-link" id="view-contributions" href="{{ route('member.contributions') }}">
                    <i class="ico-contri me-1"></i>My Contributions
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="memdetails row align-items-stretch">
                <div class="col-lg-2 align-self-stretch">
                    <!--Member Details-->
                    <div class="meminfo card mb-4 profile-photo">
                        <img src="{{ asset($user->photo ? 'storage/' . $user->photo : 'assets/images/default-profile.png') }}" alt="User Photo">
                    </div>
                    <div class="edit-profile">
                        <a class="nav-link" id="view-contributions" href="/profile/edit">
                            <i class="ico-contri me-1"></i>Edit Profile
                        </a>
                    </div>
                    <br>
                    <div class="edit-profile">
                        <a class="nav-link" id="view-contributions" href="{{ route('password.edit') }}">
                            <i class="ico-contri me-1"></i>Change Password
                        </a>
                    </div>
                    <!--/Member Details-->
                </div>
                <div class="col-lg-5 align-self-stretch">
                    <!--Address & Contact Info-->
                    <div class="meminfo card mb-4 mt-4 mt-md-4 mt-lg-0">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">
                                <h4>My Details</h4>
                            </div>
                            <div class="memdtls-mydtls">
                                <div class="d-none d-md-block">
                                    <div class="row g-3 pt-2 pb-4">
                                        <div class="col-5 border-bottom">Local Home Address</div>
                                        <div class="col-7 border-bottom">{{ $user->address }}</div>
                                        <div class="col-5 border-bottom border-bottom">Email:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->email }}</div>
                                        <div class="col-5 border-bottom border-bottom">Contact No.:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->contact_no }}</div>
                                        <div class="col-5 border-bottom border-bottom">Sex</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->sex }}</div>
                                        <div class="col-5 border-bottom border-bottom">Religion:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->religion }}</div>
                                        <div class="col-5 border-bottom border-bottom">Marital Status:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->marital_status }}</div>
                                        <div class="col-5 border-bottom border-bottom">Birthdate:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->birthdate }}</div>
                                        <div class="col-5 border-bottom border-bottom">Educational Attainment:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->education }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!---right-->
                <div class="col-lg-5 align-self-stretch">
                    <div class="meminfo card mb-4 mt-4 mt-md-4 mt-lg-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>Employment Details</h4>
                            </div>
                            <div class="memdtls-employdtls">
                                <div class="d-none d-md-block">
                                    <div class="row g-3 pt-2 pb-4">
                                        <div class="col-5 border-bottom border-bottom">Office:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->office }}</div>
                                        <div class="col-5 border-bottom border-bottom">Position:</div>
                                        <div class="col-7 border-bottom border-bottom">{{ $user->position }}</div>
                                        <div class="col-5 border-bottom">Annual Income:</div>
                                        <div class="col-7 border-bottom">{{ $user->annual_income }}</div>
                                        <div class="col-5 border-bottom">Account No.</div>
                                        <div class="col-7 border-bottom">{{ $user->employee_ID }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="meminfo card mb-4 mt-4 mt-md-4 mt-lg-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>Membership Details</h4>
                            </div>  
                            <div class="memdtls-umidssiddtls">
                                <div class="d-none d-md-block">
                                    <div class="row g-3 pt-2">
                                        <div class="col-6 border-bottom border-bottom">Membership Status: </div>
                                        <div class="col-6 border-bottom border-bottom"><span class="font-semibold {{ auth()->user()->status === 'Active' ? 'text-green-500' : 'text-red-500' }}">
                                         {{ auth()->user()->status }}
                                        </span></div>
                                        <div class="col-6 border-bottom">Membership Date:</div>
                                        <div class="col-6 border-bottom">{{ $user->membership_date }}</div>
                                        <div class="col-6 border-bottom">Username:</div>
                                        <div class="col-6 border-bottom">{{ $user->username }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
</div>
</x-app-layout>
