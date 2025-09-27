<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    {{-- Para usar assets, el archivo CSS debe estar en public/css/ o usar @vite --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"/>
</head>
<body>
    <section class="header">
        <div class="logo">
            <i class="ri-menu-line icon icon-0"></i>
            <h2>San <span>Francisco</span></h2> 
        </div>
        <div class="search--notification--profile">
            <div class="search">
                <input type="text" placeholder="Search Scdule..">
                <button><i class="ri-search-2-line"></i></button>
            </div>
            <div class="notification--profile">
                <div class="picon lock">
                    <i class="ri-lock-line"></i>
                </div>
                <div class="picon bell">
                    <i class="ri-notification-2-line"></i>
                </div>
                <div class="picon chat">
                    <i class="ri-wechat-2-line"></i>
                </div>
                <div class="picon profile">
                    <img src="{{ asset('images/logo.webp') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="main">
        <div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="" id="active--link">
                        <span class="icon icon-1"><i class="ri-layout-grid-line"></i></span>
                        <span class="sidebar--item">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-2"><i class="ri-calendar-2-line"></i></span>
                        <span class="sidebar--item">Schedule</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-3"><i class="ri-user-3-line"></i></span>
                        <span class="sidebar--item">Reliable Doctor</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-4"><i class="ri-user-line"></i></span>
                        <span class="sidebar--item">Patients</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-5"><i class="ri-line-chart-line"></i></span>
                        <span class="sidebar--item">ACtivity</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-6"><i class="ri-customer-service-line"></i></span>
                        <span class="sidebar--item">Support</span>
                    </a>
                </li>
            </ul>
            <ul class="sidebar--bottom-items">
                  <li>
                    <a href="">
                        <span class="icon icon-7"><i class="ri-settings-3-line"></i></span>
                        <span class="sidebar--item">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="icon icon-8"><i class="ri-logout-box-r-line"></i></span>
                        <span class="sidebar--item">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main--content">
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">Overview</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="lastweel">Last Week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="lastyear">Last Year</option>
                        <option value="alltime">All Time</option>
                    </select>
                </div>
                <div class="cards">
                    <div class="card card-1">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--tigle">Total Doctors</h5>
                                <h1>152</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>65%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>10%</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2%</span>
                        </div>
                    </div>

                    <div class="card card-2">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--tigle">Total Doctors</h5>
                                <h1>152</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>65%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>10%</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2%</span>
                        </div>
                    </div>

                    <div class="card card-3">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--tigle">Total Patients</h5>
                                <h1>1145</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>65%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>10%</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2%</span>
                        </div>
                    </div>

                    <div class="card card-4">
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--tigle">Total Doctors</h5>
                                <h1>152</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                        <div class="card--stats">
                            <span><i class="ri-bar-chart-fill card--icon stat--icon"></i>65%</span>
                            <span><i class="ri-arrow-up-s-fill card--icon up--arrow"></i>10%</span>
                            <span><i class="ri-arrow-down-s-fill card--icon down--arrow"></i>2%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="doctors">
                <div class="title">
                    <h2 class="section--title">Doctors</h2>
                    <div class="doctos--right--btns">
                        <select name="date" id="date" class="dropdown doctor--filter">
                            <option>Filter</option>
                            <option value="free">Free</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                        <button class="add"><i class="ri-add-line"></i>Add Doctor</button>
                    </div>
                </div>
                <div class="doctors--cards">
                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor1.webp') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="free">Free</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor2.avif') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor3.webp') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor4.webp') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor5.jpg') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor6.webp') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>

                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor7.jpg') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>
                    
                    <a href="" class="doctor--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="{{ asset('images/doctor8.avif') }}" alt="" class="box--box">
                            </div> 
                        </div>
                        <p class="scheduled">Scheduled</p>
                    </a>
                </div>
            </div>

           <div class="slider">
            <div class="wrapper">
                <input type="radio" name="slide" id="one" checked>
                <input  type="radio" name="slide" id="two">
                <input  type="radio" name="slide" id="three">
                <input type="radio" name="slide" id="four">
                <input type="radio" name="slide" id="five">
                <div class="img img-1">
                    <img src="{{ asset('images/img1.jpg') }}" alt="">
                </div>

                <div class="img img-2">
                    <img src="{{ asset('images/img2.jpg') }}" alt="">
                </div>

                <div class="img img-3">
                    <img src="{{ asset('images/img3.jpg') }}" alt="">
                </div>
                <div class="img img-4">
                    <img src="{{ asset('images/img4.jpg') }}" alt="">
                </div>
                <div class="img img-5">
                    <img src="{{ asset('images/img5.jpg') }}" alt="">
                </div>
                <div class="sliders">
                    <label for="one" class="one"></label>
                    <label for="two" class="two"></label>
                    <label for="three" class="three"></label>
                    <label for="four" class="four"></label>
                    <label for="five" class="five"></label>
                </div>
            </div>
           </div> 
        </div>
    </section>
</body>
</html>