@extends('layouts.master')
@section('title', 'Book a Consultation | P23 Africa')

@section('meta')
    <meta name="description"
        content="Book a personalized business consultation with P23 Africa. Get expert guidance on funding readiness, market positioning, strategy development, and investor engagement tailored to African entrepreneurs and growing businesses.">
    <meta name="keywords"
        content="book consultation, business consultation, P23 Africa, African entrepreneurs, business strategy, funding readiness, investor preparation, growth consulting, startup advisory, business development Africa">
    <meta name="author" content="P23 Africa">
    <meta name="generator" content="Book Consultation | P23 Africa">

    <meta property="og:title" content="Book a Consultation | P23 Africa">
    <meta property="og:description"
        content="Schedule a one-on-one consultation to strengthen your business strategy, improve funding readiness, and unlock growth opportunities with expert advisors at P23 Africa.">
    <meta property="og:image" content="{{ asset('img/mobile.png') }}">
    <meta property="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


<!--   -->

@section('content')


    <section class="schedule-conversation-section">
        <div class="box-container">
            <div class="intro-container">
                <div class="left-content">
                    <h3>Schedule Your Consultation</h3>

                    <div class="para">
                        <p>
                            Pick a convenient date and time for your session. You’ll
                            receive a confirmation email once it’s booked.
                        </p>
                    </div>

                    <div class="info">
                        <img src="{{ asset('img/left-conv-pattern.png') }}" class="content-pattern" alt="" />
                        <img src="{{ asset('img/schedule-pattern.png') }}" class="schedule-pattern" alt="" />
                        <div class="info-content">
                            <!-- SUBMIT BUTTON -->

                            <div class="btn">
                                <button class="submit-schedule">
                                    Submit
                                    <div><img src="{{ asset('img/right.png') }}" alt="" /></div>
                                </button>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- CALENDAR AREA -->
                <!-- <div class="right-content"> -->
                <div id="calendar-wrapper">
                    <div class="calendar-wrapper-container">
                        <div class="calendar-header">
                            <h3>Select Day</h3>

                            {{--  --}}
                            <div class="nav-row">
                                <div class="prev-wrapper">
                                    <button id="prevMonth">
                                        <img src="{{ asset('img/left.png') }}" alt="" />
                                    </button>
                                    <div class="prev-tooltip">
                                        You cannot go before the current month
                                    </div>
                                </div>

                                <div class="month-year-inputs">
                                    <div class="month-input-container">
                                        <input type="text" id="monthInput" placeholder="November" />
                                        <div class="month-suggestions" id="monthSuggestions"></div>
                                    </div>
                                    <input type="number" id="yearInput" placeholder="2025" min="2025" max="2050" />
                                </div>

                                <button id="nextMonth">
                                    <img src="{{ asset('img/right-arrow.png') }}" alt="" />
                                </button>
                            </div>
                        </div>

                        <div id="calendar-grid">
                            <div class="weekdays">
                                <div class="weekday">Mon</div>
                                <div class="weekday">Tue</div>
                                <div class="weekday">Wed</div>
                                <div class="weekday">Thu</div>
                                <div class="weekday">Fri</div>
                                <div class="weekday">Sat</div>
                                <div class="weekday">Sun</div>
                            </div>

                            <div id="dates"></div>
                        </div>

                        <div class="output-box mobile">
                            <p><strong>Selected Date:</strong></p>
                            <p class="selected-date">—</p>
                        </div>

                        <!-- TIME AREA  -->
                        <div class="time-box">
                            <div class="time-box-container">
                                <div class="timezone-row">
                                    <label>Set time</label>
                                    <div class="custom-dropdown timeZone" id="dropdown-timezone" tabindex="0"
                                        role="listbox" aria-label="Timezone">
                                        <button class="cd-toggle" aria-haspopup="listbox" aria-expanded="false">
                                            <span class="cd-selected">GMT</span>
                                            <span class="cd-caret">
                                                <img src="{{ asset('img/arrow-up-01.png') }}" alt="" />
                                            </span>
                                        </button>
                                        <ul class="cd-list" role="listbox" aria-hidden="true">
                                            <li role="option" data-value="GMT" class="cd-option">GMT</li>
                                            <li role="option" data-value="UTC" class="cd-option">UTC</li>
                                            <li role="option" data-value="WAT" class="cd-option">WAT</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="time-restriction-notice" id="timeRestrictionNotice" style="display: none;">
                                    <p>⏰ Same-day bookings must be scheduled at least one hour in advance.</p>
                                </div>

                                <!-- Scrollable Time Picker -->
                                <div class="scroll-time-picker">
                                    <!-- Hour Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="hour-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Hours will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Minute Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="minute-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Minutes will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Picker -->
                                    <div class="time-wheel-container">
                                        <div class="time-wheel" id="second-wheel">
                                            <div class="time-wheel-list">
                                                <!-- Seconds will be populated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- AM/PM Picker -->
                                    <div class="time-wheel-container ampm">
                                        <div class="time-wheel" id="ampm-wheel">
                                            <div class="time-wheel-list">
                                                <div class="time-wheel-item" data-value="AM">AM</div>
                                                <div class="time-wheel-item" data-value="PM">PM</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SELECTED OUTPUT -->
                            <div class="output-box desktop">
                                <p><strong>Selected Date:</strong></p>
                                <p class="selected-date">—</p>

                                <p style="margin-top: 10px">
                                    <strong>Selected Time:</strong>
                                </p>
                                <p class="selected-time">—</p>
                            </div>
                        </div>

                        <div class="output-box mobile">
                            <p style="margin-top: 10px"><strong>Selected Time:</strong></p>
                            <p class="selected-time">—</p>
                        </div>

                        <div class="mobile-btn">
                            <button class="submit-schedule">
                                Submit
                                <div><img src="{{ asset('img/right-colored-r.png') }}" alt="" /></div>
                            </button>
                        </div>

                        <div class="call-for-help">

                            <div class="callforhelp-container">
                                <p>Call For Help</p>
                                <button>
                                    <div><img src="{{ asset('img/customer-service-01.png') }}" alt="" /></div>
                                </button>
                            </div>
                        </div>

                        <!-- CHAT MODAL OVERLAY -->
                        <div id="chat-overlay" class="chat-overlay"></div>

                        <!-- CHAT SLIDE-IN PANEL -->
                        <div id="chat-panel" class="chat-panel">

                            <div class="chat-panel-container">


                                <div class="panel-container">


                                    <img class="chat-inset-pattern" src="{{ asset('img/side-chat-pattern.png') }}"
                                        alt="" />
                                    <div class="chat-header">


                                        <div class="chat-info">
                                            <div class="chat-info-left-content">
                                                <img class="arr-left" src="{{ asset('img/arrow-left.png') }}"
                                                    alt="" />
                                                <div class="avatar">
                                                    <div class="active"></div>
                                                    <img src="{{ asset('images/logo.png') }}" alt="" />
                                                </div>
                                                <div class="ct-cont">
                                                    <h3>P23 Africa</h3>
                                                    <small>Online</small>
                                                </div>
                                            </div>
                                            <div class="chat-info-right-content">
                                                <div class="call-info-icon">
                                                    <img src="{{ asset('img/phone.png') }}" alt="" />
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="chat-body" id="chat-body">
                                        <div class="msg bot">
                                            <span>
                                                Hey 👋
                                            </span>
                                        </div>
                                        <div class="msg bot">
                                            <span>
                                                Welcome to P23 Africa, what can we help you with?
                                            </span>

                                        </div>
                                    </div>

                                    <div class="chat-input-container">
                                        <div class="input-container">
                                            <input id="chat-input" placeholder="Type your message here"
                                                maxlength="1000" />
                                            <button class="send-btn" id="send-btn">
                                                <img class="arr-left" src="{{ asset('img/send-icon.png') }}"
                                                    alt="" />
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>


                        <!-- Hidden form for actual submission -->
                        <form id="scheduleForm" method="POST" action="{{ locale_route('consultation.schedule.submit') }}"
                            style="display: none;">
                            @csrf
                            <input type="hidden" name="booking" value="{{ $application->booking_token }}">
                            <input type="hidden" name="session_date" id="hiddenDate">
                            <input type="hidden" name="session_time" id="hiddenTime">
                            <input type="hidden" name="timezone" id="hiddenTimezone">
                        </form>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </div>

        <!-- POPUP MODAL -->
        <div id="scheduleModal" class="modal-overlay">
            <div class="modal-box">
                <h3>consultation confirmed!</h3>
                <p>A confirmation email containing the date, time, and Google Meet link has been sent to your registered
                    email address.</p>
                <h4 id="modalDateText"></h4>
                <h4 id="modalTimeText"></h4>

                <a href="#" class="submit-btn-modal">
                    Open customer@example.com
                </a>
                <a href="{{ route('consultation.index') }}" class="submit-btn-modal">
                    Return to Homepage
                </a>
            </div>
        </div>
    </section>

    <!-- FLASH MESSAGE CONTAINER -->
    <div id="flashContainer"></div>
@endsection



