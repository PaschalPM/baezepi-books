<?php

/*
|--------------------------------------------------------------------------
| Site content
|--------------------------------------------------------------------------
|
| All copy for the public site lives here so it can be edited without
| touching the views. Everything below is placeholder content.
|
*/

return [

    'name' => 'Baeze Publishing',
    'tagline' => 'Independent books on culture, technology, influence and the work of making a life.',
    // Default search/share description for pages that don't set their own (keep under ~160 characters).
    'description' => 'Independent books on culture, technology, influence and craft. Find your next read from Baeze Publishing.',

    'seo' => [
        // Image used when a page is shared on social media and has no image of its own.
        // Put a 1200×630 image in public/images/ and set e.g. 'images/share.jpg'.
        'share_image' => null,
        // X/Twitter handle without the @, e.g. 'baezepublishing'.
        'twitter' => null,
        'locale' => 'en_US',
        // Profiles that represent the publisher (Instagram, LinkedIn, etc.), used in structured data.
        'same_as' => [],
    ],

    'email' => 'info@baezepi.com',
    'currency_symbol' => '$',
    'phone' => '301 637 0818',

    'author' => [
        'name' => 'Patrick Okeke',
        'short_bio' => 'Patrick Okeke\'s books follow the seams between individual identity and collective story, between technology and the lives it changes.',
        'bio' => [
            'I started writing because I wanted to understand things slowly. The world keeps insisting we move at the speed of feeds. Books are how I refuse.',
            'My early years were spent in Lagos, surrounded by stories that refused to fit into single categories: markets that doubled as theatres, prayers that doubled as politics, friendships that doubled as economies. That refusal of neat lines became the guiding thread of my work.',
            'Today I write across four loose territories: cultural identity, technology and creators, the inner life of builders, and the ethics of attention. The books talk to each other. Read in any order, they form a single ongoing argument: that craft, care and slowness are not nostalgic luxuries but radical tools.',
            'When I am not writing I am usually reading, walking, or quietly arguing with a draft. I publish independently because I want the reader, not the algorithm, to be the editor I write toward.',
        ],
    ],

    'nav' => [
        ['label' => 'The Books', 'route' => 'books.index'],
        ['label' => 'Notes', 'route' => 'notes.index'],
        ['label' => 'The Author', 'route' => 'about'],
        ['label' => 'Correspondence', 'route' => 'contact'],
    ],

    'topics' => [
        'identity' => [
            'title' => 'Identity',
            'slug' => 'books-about-cultural-identity',
            'blurb' => 'How we see ourselves, name our story, and navigate the lines drawn around us.',
        ],
        'influence' => [
            'title' => 'Influence',
            'slug' => 'influence-books',
            'blurb' => 'How power works, attention moves, and ideas spread.',
        ],
        'leadership' => [
            'title' => 'Leadership',
            'slug' => 'books-about-leadership',
            'blurb' => 'How people build authority, set direction, and lead in public.',
        ],
        'success' => [
            'title' => 'Success',
            'slug' => 'books-about-success',
            'blurb' => 'How purpose, persistence, and disciplined work become a life that lasts.',
        ],
        'creativity' => [
            'title' => 'Creativity',
            'slug' => 'books-on-creativity',
            'blurb' => 'How an idea becomes useful work without losing its point of view.',
        ],
        'ambition' => [
            'title' => 'Ambition',
            'slug' => 'books-about-ambition',
            'blurb' => 'How purpose, direction, and persistence turn a serious aim into meaningful work.',
        ],
    ],

    // Books live in the database; manage them at /admin.

    /*
    | Blog: categories at /blog/{category}, posts at /blog/{category}/{post}.
    | Each post's body lives in resources/views/blog/posts/{post}.blade.php.
    | A post's visual is an image, or a drawn "timeline" / "outline" graphic
    | with separate labels for the small card and the large article header.
    */
    'blog' => [
        'creator-practice' => [
            'title' => 'Creator Practice',
            'intro' => 'Thoughtful guidance on building a body of work, earning attention with substance, and staying human while you do it.',
            'image' => [
                'webp' => 'images/blog/creator-practice-category-e1142159c059f669.webp',
                'jpg' => 'images/blog/creator-practice-category-1de73766adcbdd4e.jpg',
                'alt' => 'Writer\'s desk with books, camera, and open notebook in morning light',
            ],
            'posts' => [
                'how-long-does-it-take-to-write-a-book' => [
                    'title' => 'How Long Does It Take to Write a Book? A Practical Timeline',
                    'excerpt' => 'A practical way to estimate how long a book may take, choose a pace you can sustain, and keep the work moving without turning the calendar into a verdict.',
                    'date' => '2026-10-07',
                    'visual' => [
                        'type' => 'timeline',
                        'label' => 'A writing timeline moving from a book idea through drafting and revision to a finished manuscript',
                        'card' => ['Plan', 'Draft', 'Shape', 'Finish'],
                        'hero' => ['Question', 'Plan', 'Draft', 'Rest', 'Revise', 'Finish'],
                    ],
                ],
                'how-to-outline-a-book' => [
                    'title' => 'How to Outline a Book: A Practical 7-Step Guide',
                    'excerpt' => 'A practical seven-step way to outline a book, find its central question, shape chapters, and leave room for the discoveries that make a draft feel alive.',
                    'date' => '2026-09-23',
                    'visual' => [
                        'type' => 'outline',
                        'label' => 'A simple visual map for planning a book',
                        'card' => ['01', '02', '03', '04', '05'],
                        'hero' => ['Idea', 'Reader', 'Path', 'Book'],
                    ],
                ],
                'how-to-start-writing' => [
                    'title' => 'How to Start Writing: A Practical 6-Step Guide',
                    'excerpt' => 'A practical six-step way to begin writing, choose a real subject, make a first draft, and build a return path for the next session.',
                    'date' => '2026-09-16',
                    'visual' => [
                        'type' => 'image',
                        'webp' => 'images/blog/start-writing-featured-d5bc4dbd59adc428.webp',
                        'jpg' => 'images/blog/start-writing-featured-5245d44cc482e6b2.jpg',
                        'alt' => 'Open blank notebook, fountain pen, books, and tea on a sunlit wooden desk',
                    ],
                ],
                'find-your-writing-voice' => [
                    'title' => 'How to Find Your Writing Voice: 7 Exercises',
                    'excerpt' => 'Seven practical writing exercises to help you notice your natural language, revise with more confidence, and make work that sounds like you.',
                    'date' => '2026-09-09',
                    'visual' => [
                        'type' => 'image',
                        'webp' => 'images/blog/writing-voice-featured-0fe407f61f272f8f.webp',
                        'jpg' => 'images/blog/writing-voice-featured-2220c3d05ed6fda0.jpg',
                        'alt' => 'Fountain pen writing on a lined page',
                    ],
                ],
                'build-creative-practice' => [
                    'title' => 'How to Build a Creative Practice That Lasts',
                    'excerpt' => 'A practical way to make room for meaningful work, return after difficult days, and build a practice you can trust.',
                    'date' => '2026-09-02',
                    'visual' => [
                        'type' => 'image',
                        'webp' => 'images/blog/creative-practice-featured-2d191b3bbcde49fe.webp',
                        'jpg' => 'images/blog/creative-practice-featured-28e5f6ebca6d6f2c.jpg',
                        'alt' => 'Open notebook, fountain pen, books, and tea on a sunlit wooden desk',
                    ],
                ],
                'how-to-become-an-influencer' => [
                    'title' => 'How to Become an Influencer Without Losing Your Voice',
                    'excerpt' => 'A practical way to build an audience around useful ideas, real work, and trust that lasts longer than a trend.',
                    'date' => '2026-08-26',
                    'visual' => [
                        'type' => 'image',
                        'webp' => 'images/blog/how-to-become-influencer-featured-793ddc4ebe44bd1a.webp',
                        'jpg' => 'images/blog/how-to-become-influencer-featured-2295af14fa268261.jpg',
                        'alt' => 'Microphone, camera, notebook, and laptop on a creator\'s desk',
                    ],
                ],
            ],
        ],
    ],

    /*
    | Details used by the Privacy, Terms and Cookie pages. Fill these in and
    | have the pages reviewed by a lawyer for your jurisdiction before launch.
    */
    'legal' => [
        'entity' => 'Baeze Publishing Inc.',
        'address' => '4445 Willard Ave, Ste. 600 Chevy Chase, MD 20815',
        'privacy_email' => 'privacy@example.com',
        // Governing law and venue, e.g. 'Delaware' and 'New Castle County, Delaware'.
        'state' => '[State]',
        'venue' => '[County], [State]',
        // DMCA designated agent: register at https://www.copyright.gov/dmca-directory/
        'dmca_agent' => 'Copyright Agent',
        'dmca_email' => 'copyright@example.com',
        'updated' => '2026-10-09',
        // COPPA threshold.
        'min_age' => 13,
        // Where newsletter addresses are stored once a provider is connected.
        'newsletter_provider' => 'our email newsletter provider',
    ],

    'stats' => [
        ['value' => '5', 'label' => 'Books published'],
        ['value' => '6', 'label' => 'Themes explored'],
        ['value' => '40+', 'label' => 'Essays and notes'],
        ['value' => '12', 'label' => 'Countries with readers'],
    ],

];
