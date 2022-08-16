<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://anshell.com</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://anshell.com/contact-us</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/agents</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/sell</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/sitemap</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/privacy-policy</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/terms-of-use</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/digital-millennium-copyright-act-notice</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/all-states</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>https://anshell.com/category/florida-real-estate-exam</loc>
        <lastmod>{{ date('c', time()) }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @foreach ($pages as $page)
        <url>
            <loc>{{ $siteurl }}/{{ $page->slug }}</loc>
            <lastmod>{{ $page->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>