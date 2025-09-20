@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static pages --}}
    @foreach($urls as $url)
        <sitemap>
            <loc>{{ $url['loc'] }}</loc>
            <lastmod>{{ $url['lastmod'] }}</lastmod>
        </sitemap>
    @endforeach

    {{-- Child sitemaps (like products sitemap) --}}
    @foreach($sitemaps as $sitemap)
        <sitemap>
            <loc>{{ $sitemap }}</loc>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
        </sitemap>
    @endforeach
</sitemapindex>
