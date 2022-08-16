<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($states as $state)
        @if ($state->name != null)
        <url>
            <loc>{{ $siteurl . '/real-estate/Florida' }}/{{ preg_replace('/[[:space:]]+/', '-', $state->name) }}</loc>
            <lastmod>{{ $state->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
        @endif
    @endforeach
</urlset>