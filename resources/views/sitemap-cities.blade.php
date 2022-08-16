<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($cities as $city)
        @if ($city->name != null)
        <url>
            <loc>{{ $siteurl . '/real-estate/Florida' }}/{{ preg_replace('/[[:space:]]+/', '-', $city->name) }}</loc>
            <lastmod>{{ $city->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
        @endif
    @endforeach
</urlset>