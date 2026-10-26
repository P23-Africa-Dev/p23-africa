# -*- coding: utf-8 -*-
from pathlib import Path
root = Path(r'c:/xampp/htdocs/P23 Africa')
missing = []

def sub(rel, old, new):
    p = root / rel
    text = p.read_text(encoding='utf-8')
    if old not in text:
        missing.append(rel + ' :: ' + old[:140].replace('\n', ' / '))
        return
    p.write_text(text.replace(old, new), encoding='utf-8')

A = "\u2019"  # curly apostrophe

# all-events
sub('resources/views/all-events.blade.php', "@section('title', 'All Events | P23 Africa')", "@section('title', __('events.all_title'))")
META = "Expand and grow your business in Africa with expert business consulting. P23 Africa helps entrepreneurs, SMEs, and international businesses with strategy, expansion, marketing, operational efficiency and tailored solutions to thrive in African markets. Get started today!"
GEN = "Business Consulting & Growth Solutions for Africa | P23 Africa"
sub('resources/views/all-events.blade.php', META, "{{ __('events.meta') }}")
sub('resources/views/all-events.blade.php', GEN, "{{ __('events.meta_generator') }}")
sub('resources/views/all-events.blade.php', 'alt="Card image"', "alt=\"{{ __('events.alt_card') }}\"")
sub('resources/views/all-events.blade.php', 'placeholder="Search your preferred event"', "placeholder=\"{{ __('events.search_ph') }}\"")
sub('resources/views/all-events.blade.php', 'Happening This Month', "{{ __('events.this_month') }}")
sub('resources/views/all-events.blade.php', 'Happening Next Month', "{{ __('events.next_month') }}")
sub('resources/views/all-events.blade.php', 'Happening In Two Month', "{{ __('events.two_months') }}")
sub('resources/views/all-events.blade.php', 'All BRN Events', "{{ __('events.all_brn') }}")
sub('resources/views/all-events.blade.php', 'Loading...', "{{ __('events.loading') }}")
sub('resources/views/all-events.blade.php', 'Book A Seat →', "{{ __('events.book_seat') }} →")
sub('resources/views/all-events.blade.php', 'el.innerHTML = "Event Started";', "el.innerHTML = @json(__('events.event_started'));")
sub('resources/views/all-events.blade.php', 'el.innerHTML = `Happening in ${hours}h ${minutes}m`;', "el.innerHTML = @json(__('events.happening_in')).replace(':hours', hours).replace(':minutes', minutes);")
sub('resources/views/all-events.blade.php', 'No events found.', "{{ __('events.none_found') }}")
sub('resources/views/all-events.blade.php', 'Error loading events.', "{{ __('events.load_error') }}")

# show-event
sub('resources/views/show-event.blade.php', 'alt="Frame"', "alt=\"{{ __('events.alt_card') }}\"")
sub('resources/views/show-event.blade.php', 'alt="Main Event"', "alt=\"{{ __('events.alt_event') }}\"")
sub('resources/views/show-event.blade.php', 'alt="Event Frame"', "alt=\"{{ __('events.alt_card') }}\"")
sub('resources/views/show-event.blade.php', 'About This Event', "{{ __('events.about') }}")
sub('resources/views/show-event.blade.php', "?? 'Google Meet'", "?? __('events.google_meet')")
sub('resources/views/show-event.blade.php', '<strong>1hr 30mins</strong>', "<strong>{{ __('events.duration') }}</strong>")
sub('resources/views/show-event.blade.php', 'Book A\n                            Seat', "{{ __('events.book_seat') }}")
sub('resources/views/show-event.blade.php', 'Share This Event', "{{ __('events.share') }}")
sub('resources/views/show-event.blade.php', 'Edit Event', "{{ __('events.edit') }}")
sub('resources/views/show-event.blade.php', 'Link copied to clipboard!', "{{ __('events.copied') }}")
sub('resources/views/show-event.blade.php', 'Enter Your Seat Code', "{{ __('events.seat_code_title') }}")
sub('resources/views/show-event.blade.php', 'placeholder="Enter your seat code"', "placeholder=\"{{ __('events.seat_code_ph') }}\"")
sub('resources/views/show-event.blade.php', 'Join Event', "{{ __('events.join') }}")

# live
sub('resources/views/events/live.blade.php', "@section('title', 'Live Event | P23 Africa')", "@section('title', __('events.live_title'))")
sub('resources/views/events/live.blade.php', '<h2 class="mb-4">Welcome {{ $seat->name }}</h2>', '<h2 class="mb-4">{{ __(\'events.welcome\', [\'name\' => $seat->name]) }}</h2>')
sub('resources/views/events/live.blade.php', f'You{A}ve joined the live session!', "{{ __('events.joined') }}")
sub('resources/views/events/live.blade.php', '<strong>Date:</strong>', "<strong>{{ __('events.date') }}</strong>")
sub('resources/views/events/live.blade.php', '<strong>Time:</strong>', "<strong>{{ __('events.time') }}</strong>")
sub('resources/views/events/live.blade.php', 'alt="Speaker"', "alt=\"{{ __('events.alt_speaker') }}\"")

# feedback
sub('resources/views/feedback/form.blade.php', f'We{A}d love your feedback', "{{ __('events.feedback_title') }}")
sub('resources/views/feedback/form.blade.php', 'How would you rate this event?', "{{ __('events.rate') }}")
sub('resources/views/feedback/form.blade.php', '> Excellent</label>', "> {{ __('events.excellent') }}</label>")
sub('resources/views/feedback/form.blade.php', '> Good</label>', "> {{ __('events.good') }}</label>")
sub('resources/views/feedback/form.blade.php', '> Fair</label>', "> {{ __('events.fair') }}</label>")
sub('resources/views/feedback/form.blade.php', '> Poor</label>', "> {{ __('events.poor') }}</label>")
sub('resources/views/feedback/form.blade.php', '<label>Comments</label>', "<label>{{ __('events.comments') }}</label>")
sub('resources/views/feedback/form.blade.php', 'Submit Feedback', "{{ __('events.submit_feedback') }}")
sub('resources/views/feedback/thankyou.blade.php', 'Thank You!', "{{ __('events.thanks') }}")
sub('resources/views/feedback/thankyou.blade.php', 'Your feedback has been submitted successfully. We appreciate your time!', "{{ __('events.thanks_body') }}")

# book seat
sub('resources/views/include/book-seat.blade.php', 'aria-label="Close"', "aria-label=\"{{ __('events.close') }}\"")
sub('resources/views/include/book-seat.blade.php', '''Book
                        your seat to be a part of this exclusive event and gain valuable insights from trusted
                        experts.''', "{{ __('events.book_intro') }}")
sub('resources/views/include/book-seat.blade.php', 'Submit <i class="bi bi-arrow-right ms-2"></i>', "{{ __('events.submit') }} <i class=\"bi bi-arrow-right ms-2\"></i>")
sub('resources/views/include/book-seat.blade.php', "Thank you for signing up. We've sent event details to your email <br>\n                <span class=\"\">— please check your inbox.</span>", "{{ __('events.signup_thanks') }} <br>\n                <span class=\"\">{{ __('events.check_inbox') }}</span>")
sub('resources/views/include/book-seat.blade.php', 'View Other Events', "{{ __('events.view_other') }}")

# archive
sub('resources/views/archive.blade.php', "@section('title', 'Archive | P23 Africa')", "@section('title', __('resources.archive_title'))")
ARCH = "Explore Expert Articles, Market Trends, And Practical Advice To Scale Your Business Across Africa And Beyond."
sub('resources/views/archive.blade.php', ARCH + " | P23 Africa", "{{ __('resources.meta_generator') }}")
sub('resources/views/archive.blade.php', ARCH, "{{ __('resources.meta') }}")
sub('resources/views/archive.blade.php', 'Explore Our Archive of Business <span>Blogs</span> And <span>Reports</span>', "{!! __('resources.hero') !!}")
sub('resources/views/archive.blade.php', 'placeholder="Search your preferred Blog"', "placeholder=\"{{ __('resources.search_ph') }}\"")
sub('resources/views/archive.blade.php', 'View all Archived Blogs', "{{ __('resources.view_all') }}")

sub('resources/views/archive-list.blade.php', "@section('title', 'Archive List | P23 Africa')", "@section('title', __('resources.list_title'))")
sub('resources/views/archive-list.blade.php', ARCH + " | P23 Africa", "{{ __('resources.meta_generator') }}")
sub('resources/views/archive-list.blade.php', ARCH, "{{ __('resources.meta') }}")
sub('resources/views/archive-list.blade.php', 'placeholder="Search your preferred Blog"', "placeholder=\"{{ __('resources.search_ph') }}\"")
sub('resources/views/archive-list.blade.php', "?? 'No Category'", "?? __('resources.no_category')")
sub('resources/views/archive-list.blade.php', 'Written by {{ $blog->user->name }}', "{{ __('resources.written_by', ['name' => $blog->user->name]) }}")
sub('resources/views/archive-list.blade.php', 'Other Categories:', "{{ __('resources.other_categories') }}")
sub('resources/views/archive-list.blade.php', 'Get Latest Insights →', "{{ __('resources.latest') }}")

print('done', len(missing))
Path(r'c:/xampp/htdocs/P23 Africa/storage/strip/miss1.txt').write_text('\n'.join(missing), encoding='utf-8')
