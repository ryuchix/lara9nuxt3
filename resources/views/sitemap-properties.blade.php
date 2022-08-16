<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($properties as $property)
        @if ($property->City != null || $property->ListingId != null || $property->UnparsedAddress != null || $property->PropertyType != null)
        <url>
            <loc>{{ $siteurl . '/Florida' }}/{{ preg_replace('/[[:space:]]+/', '-', $property->City != null ? $property->City : '') }}/MLS-{{ $property->ListingId != null ? $property->ListingId : '' }}-{{ preg_replace('/[^A-Za-z0-9-]+/', '-', $property->UnparsedAddress != null ? substr($property->UnparsedAddress, 0, strpos($property->UnparsedAddress, ",")) : '') }}/{{ preg_replace('/[[:space:]]+/', '-', $property->PropertyType != null ? $property->PropertyType : '') }}</loc>
            <lastmod>{{ $property->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
        @endif
    @endforeach
</urlset>