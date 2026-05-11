import re
import os

with open('resources/views/pages/dokumen/dokumen.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract styles
style_match = re.search(r'<style>(.*?)</style>', content, re.DOTALL)
if style_match:
    style_content = style_match.group(1)
    os.makedirs('public/assets/auth/backend/css', exist_ok=True)
    with open('public/assets/auth/backend/css/dokumen.css', 'w', encoding='utf-8') as f:
        f.write(style_content.strip() + '\n')

# Extract scripts at the bottom (excluding standard ones)
script_match = re.search(r'<script>(.*?)</script>(?=\s*</body>)', content, re.DOTALL)
if script_match:
    script_content = script_match.group(1)
    os.makedirs('public/assets/auth/backend/js', exist_ok=True)
    with open('public/assets/auth/backend/js/dokumen.js', 'w', encoding='utf-8') as f:
        f.write(script_content.strip() + '\n')

# Extract main layout content
main_match = re.search(r'<main[^>]*>(.*?)</main>', content, re.DOTALL)
if main_match:
    main_content = main_match.group(1).strip()
    blade_content = f"""<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{{{ asset('assets/auth/backend/css/dokumen.css') }}}}">
    @endpush

    @push('js')
        <!-- Any specific JS libs from HTML should be here, assuming standard ones are in master layout -->
        <script src="{{{{ asset('assets/auth/backend/js/dokumen.js') }}}}"></script>
    @endpush

{main_content}

</x-master-layout>
"""
    with open('resources/views/pages/dokumen/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(blade_content)

print("Extraction complete")
