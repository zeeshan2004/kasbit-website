<x-admin-layout :title="'Home CMS'" :header="'Home Page CMS'">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-kasbitBlue mb-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-images text-kasbitBlue text-xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">Automatic Hero Carousel</h2>
            </div>

            <form method="POST" action="{{ route('home.cms.hero-slides.store') }}" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div id="hero-slide-fields" class="space-y-4">
                    <div class="hero-slide-field border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-800">Carousel Slide 1</h3>
                            <button type="button" onclick="removeHeroSlideField(this)" class="hidden text-red-600 hover:text-red-700 text-sm font-semibold">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Carousel Slide Title</label>
                                <input type="text" name="slides[0][title]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue" placeholder="Shape Your Future With Excellence">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Carousel Slide Image</label>
                                <input type="file" name="slides[0][image]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Carousel Slide Subtitle</label>
                                <textarea name="slides[0][subtitle]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue" placeholder="Modern education for future leaders and innovators."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="slides[0][button_text]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue" placeholder="Explore Programs">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Button Link</label>
                                <input type="text" name="slides[0][button_link]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue" placeholder="/programs">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" min="0" name="slides[0][sort_order]" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                            </div>
                            <div class="flex items-end">
                                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 pb-2">
                                    <input type="hidden" name="slides[0][is_active]" value="0">
                                    <input type="checkbox" name="slides[0][is_active]" value="1" checked class="rounded border-gray-300">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <button type="button" onclick="addHeroSlideField()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-semibold">
                        <i class="fa-solid fa-plus mr-2"></i>Add Another Carousel Slide
                    </button>
                    <button type="submit" class="px-5 py-2 bg-kasbitBlue hover:bg-blue-800 text-white rounded-lg shadow">
                        Save Carousel Slides
                    </button>
                </div>
            </form>

            <div class="space-y-4">
                @forelse($heroSlides as $slide)
                    <form method="POST" action="{{ route('home.cms.hero-slides.update', $slide) }}" enctype="multipart/form-data" class="border border-gray-200 rounded-lg p-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <img src="{{ asset($slide->image_url) }}" alt="Hero carousel slide" class="h-28 w-full object-cover rounded-lg shadow">
                            </div>
                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input type="text" name="title" value="{{ $slide->title }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Title">
                                <input type="file" name="image" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <textarea name="subtitle" rows="2" class="md:col-span-2 w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Subtitle">{{ $slide->subtitle }}</textarea>
                                <input type="text" name="button_text" value="{{ $slide->button_text }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Button text">
                                <input type="text" name="button_link" value="{{ $slide->button_link }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Button link">
                                <input type="number" min="0" name="sort_order" value="{{ $slide->sort_order }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Order">
                                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <input type="checkbox" name="is_active" value="1" @checked($slide->is_active) class="rounded border-gray-300">
                                    Active
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                                Update
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('home.cms.hero-slides.destroy', $slide) }}" onsubmit="return confirm('Delete this hero slide?')" class="-mt-14 mr-24 flex justify-end">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                            Delete
                        </button>
                    </form>
                @empty
                    <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                        No carousel slides added yet.
                    </div>
                @endforelse
            </div>
        </div>

        <div id="news-cms" class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 mb-6 scroll-mt-20">
            <div class="flex items-center mb-5">
                <i class="fa-solid fa-newspaper text-blue-500 text-xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">News &amp; Events Carousel</h2>
            </div>

            <form method="POST" action="{{ route('home.cms.news-background.update') }}" enctype="multipart/form-data" class="border border-gray-200 rounded-lg p-4 mb-8 bg-gray-50">
                @csrf
                <h3 class="text-lg font-bold text-gray-800 mb-4">Section Background</h3>
                @if($home->news_bg ?? false)
                    <div class="relative mb-4 inline-block">
                        <img src="{{ asset($home->news_bg) }}" alt="News & Events background" class="h-40 w-72 object-cover rounded-lg shadow">
                        <button type="button" onclick="document.getElementById('delete_news_bg').value='1'; this.closest('.relative').style.display='none';" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg" title="Remove background" aria-label="Remove background">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif
                <input type="file" name="news_bg" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <input type="hidden" name="delete_news_bg" id="delete_news_bg" value="0">
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Background
                    </button>
                </div>
            </form>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Add News Slides</h3>
            <form method="POST" action="{{ route('news-items.store') }}" enctype="multipart/form-data" class="mb-8">
                @csrf
                <div id="news-item-fields" class="space-y-4">
                    <div class="news-item-field border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-800">News Slide 1</h4>
                            <button type="button" class="remove-news-item hidden text-red-600 hover:text-red-800" title="Remove slide" aria-label="Remove slide">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">News Title</label>
                                <input type="text" name="news_items[0][title]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="News or event title">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">News Image</label>
                                <input type="file" name="news_items[0][image]" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">News Text</label>
                                <textarea name="news_items[0][description]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="News or event details"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Read More Link</label>
                                <input type="text" name="news_items[0][link]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="/news/example">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" min="0" name="news_items[0][sort_order]" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <label class="md:col-span-2 inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="news_items[0][is_active]" value="1" checked class="rounded border-gray-300">
                                Show on frontend
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                    <button type="button" id="add-news-item" class="px-4 py-2 border border-kasbitBlue text-kasbitBlue rounded-lg hover:bg-blue-50 transition font-medium">
                        <i class="fa-solid fa-plus mr-2"></i>Add Another News Slide
                    </button>
                    <button type="submit" class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Save News Slides
                    </button>
                </div>
            </form>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit News Slides</h3>
            <div class="space-y-4">
                @forelse($newsItems as $newsItem)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <form method="POST" action="{{ route('news-items.update', $newsItem) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <img src="{{ asset($newsItem->image_url) }}" alt="{{ $newsItem->title }}" class="h-40 w-full object-cover rounded-lg shadow">
                                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <input type="text" name="title" value="{{ $newsItem->title }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="News title">
                                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <textarea name="description" rows="3" class="md:col-span-2 w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="News text">{{ $newsItem->description }}</textarea>
                                    <input type="text" name="link" value="{{ $newsItem->link }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Read more link">
                                    <input type="number" min="0" name="sort_order" value="{{ $newsItem->sort_order }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <input type="checkbox" name="is_active" value="1" @checked($newsItem->is_active) class="rounded border-gray-300">
                                        Show on frontend
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">Update News Slide</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('news-items.destroy', $newsItem) }}" onsubmit="return confirm('Delete this news slide?')" class="-mt-10 mr-44 flex justify-end">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete</button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                        No news slides added yet.
                    </div>
                @endforelse
            </div>
        </div>

        <form method="POST"
              action="{{ route('home.cms.update') }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf

            <!-- About Section -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-kasbitGold">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-book text-kasbitGold text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">About, Vision & Mission</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">About Label</label>
                        <input type="text"
                               name="about_label"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                               placeholder="WELCOME TO"
                               value="{{ $home->about_label ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">About Title</label>
                        <input type="text"
                               name="about_title"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                               placeholder="About section title"
                               value="{{ $home->about_title ?? '' }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">About Description</label>
                        <textarea name="about_description"
                                  rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                                  placeholder="Enter detailed about section text">{{ $home->about_description ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">About Image</label>
                        @if($home->about_image ?? false)
                            <div class="relative mb-3 inline-block">
                                <img src="{{ asset($home->about_image) }}" alt="About" class="h-40 w-64 object-cover rounded-lg shadow">
                                <button type="button" onclick="deleteImage('about_image')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @endif
                        <input type="file"
                               name="about_image"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                        <input type="hidden" name="delete_about_image" id="delete_about_image" value="0">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vision Title</label>
                        <input type="text"
                               name="vision_title"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue"
                               placeholder="The Vision of KASBIT"
                               value="{{ $home->vision_title ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mission Title</label>
                        <input type="text"
                               name="mission_title"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue"
                               placeholder="The Mission of KASBIT"
                               value="{{ $home->mission_title ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vision Text</label>
                        <textarea name="vision"
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                                  placeholder="Enter vision statement">{{ $home->vision ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mission Text</label>
                        <textarea name="mission"
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                                  placeholder="Enter mission statement">{{ $home->mission ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-2">
                        <button type="submit"
                                class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save About Section
                        </button>
                    </div>
                </div>
            </div>

            <!-- Locations Section -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-map-location-dot text-purple-500 text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Our Locations</h2>
                </div>

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Locations Title</label>
                        <input type="text"
                               name="location_title"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                               placeholder="Our Locations"
                               value="{{ $home->location_title ?? '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Locations Description</label>
                        <textarea name="location_description"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                                  placeholder="Enter locations description">{{ $home->location_description ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Location 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-3">Location 1 (SMCHS)</h3>
                        <div class="space-y-3">
                            <input type="text"
                                   name="location1_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Location name"
                                   value="{{ $home->location1_name ?? '' }}">
                            <input type="text"
                                   name="location1_map_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Google Maps link"
                                   value="{{ $home->location1_map_url ?? '' }}">
                            <input type="file"
                                   name="location1_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            @if($home->location1_image ?? false)
                                <div class="relative mt-2 inline-block">
                                    <img src="{{ asset($home->location1_image) }}" alt="Location 1" class="w-32 h-24 object-cover rounded">
                                    <button type="button" onclick="deleteImage('location1_image')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endif
                            <input type="hidden" name="delete_location1_image" id="delete_location1_image" value="0">
                        </div>
                    </div>

                    <!-- Location 2 -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-3">Location 2 (Hyderi)</h3>
                        <div class="space-y-3">
                            <input type="text"
                                   name="location2_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Location name"
                                   value="{{ $home->location2_name ?? '' }}">
                            <input type="text"
                                   name="location2_map_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Google Maps link"
                                   value="{{ $home->location2_map_url ?? '' }}">
                            <input type="file"
                                   name="location2_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            @if($home->location2_image ?? false)
                                <div class="relative mt-2 inline-block">
                                    <img src="{{ asset($home->location2_image) }}" alt="Location 2" class="w-32 h-24 object-cover rounded">
                                    <button type="button" onclick="deleteImage('location2_image')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endif
                            <input type="hidden" name="delete_location2_image" id="delete_location2_image" value="0">
                        </div>
                    </div>

                    <!-- Location 3 -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-3">Location 3 (Gulshan)</h3>
                        <div class="space-y-3">
                            <input type="text"
                                   name="location3_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Location name"
                                   value="{{ $home->location3_name ?? '' }}">
                            <input type="text"
                                   name="location3_map_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-kasbitBlue"
                                   placeholder="Google Maps link"
                                   value="{{ $home->location3_map_url ?? '' }}">
                            <input type="file"
                                   name="location3_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            @if($home->location3_image ?? false)
                                <div class="relative mt-2 inline-block">
                                    <img src="{{ asset($home->location3_image) }}" alt="Location 3" class="w-32 h-24 object-cover rounded">
                                    <button type="button" onclick="deleteImage('location3_image')" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endif
                            <input type="hidden" name="delete_location3_image" id="delete_location3_image" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Home CMS
                </button>
            </div>

        </form>

        <div id="video-tour-cms" class="bg-white rounded-lg shadow-md p-6 border-l-4 border-cyan-500 mt-6 scroll-mt-20">
            <div class="flex items-center mb-5">
                <i class="fa-solid fa-video text-cyan-600 text-xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">VIDEO TOUR OF KASBIT</h2>
            </div>

            <form method="POST" action="{{ route('home.cms.video-tour.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Section Title</label>
                        <input type="text"
                               name="video_tour_title"
                               value="{{ $home->video_tour_title ?? 'VIDEO TOUR OF KASBIT' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Video</label>
                        <input type="file"
                               name="video_tour_file"
                               accept="video/mp4,video/webm,video/quicktime"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="mt-2 text-xs text-gray-500">MP4, WebM or MOV. Uploading a file replaces the YouTube link.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Video Link</label>
                        <input type="text"
                               name="video_tour_url"
                               value="{{ $home->video_tour_url ?? '' }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                        <p class="mt-2 text-xs text-gray-500">Use either an uploaded video or a YouTube link.</p>
                    </div>

                    @if($home->video_tour_file ?? false)
                        <div class="md:col-span-2 border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <video controls preload="metadata" class="w-full max-w-2xl rounded-lg">
                                <source src="{{ asset($home->video_tour_file) }}">
                            </video>
                            <label class="inline-flex items-center gap-2 mt-3 text-sm font-semibold text-red-600">
                                <input type="checkbox" name="delete_video_tour_file" value="1" class="rounded border-gray-300">
                                Remove current video
                            </label>
                        </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Optional Video Thumbnail</label>
                        <input type="file"
                               name="video_tour_poster"
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    @if($home->video_tour_poster ?? false)
                        <div class="md:col-span-2">
                            <img src="{{ asset($home->video_tour_poster) }}" alt="Video thumbnail" class="w-64 h-36 object-cover rounded-lg shadow">
                            <label class="inline-flex items-center gap-2 mt-3 text-sm font-semibold text-red-600">
                                <input type="checkbox" name="delete_video_tour_poster" value="1" class="rounded border-gray-300">
                                Remove thumbnail
                            </label>
                        </div>
                    @endif

                    <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox"
                                   name="video_tour_is_active"
                                   value="1"
                                   @checked($home->video_tour_is_active ?? false)
                                   class="rounded border-gray-300">
                            Show video tour on frontend
                        </label>
                        <button type="submit" class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>Save Video Tour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

<script data-admin-page-script>
let heroSlideIndex = 1;
let newsItemIndex = 1;

function addHeroSlideField() {
    const wrapper = document.getElementById('hero-slide-fields');
    const firstField = wrapper.querySelector('.hero-slide-field');
    const newField = firstField.cloneNode(true);
    const slideNumber = heroSlideIndex + 1;

    newField.querySelector('h3').textContent = 'Carousel Slide ' + slideNumber;
    newField.querySelector('button[type="button"]').classList.remove('hidden');

    newField.querySelectorAll('input, textarea').forEach((field) => {
        field.name = field.name.replace(/slides\[\d+\]/, 'slides[' + heroSlideIndex + ']');

        if (field.type === 'checkbox') {
            field.checked = true;
            return;
        }

        if (field.type === 'hidden') {
            field.value = '0';
            return;
        }

        if (field.type === 'number') {
            field.value = heroSlideIndex;
            return;
        }

        field.value = '';
    });

    wrapper.appendChild(newField);
    heroSlideIndex++;
}

function removeHeroSlideField(button) {
    button.closest('.hero-slide-field').remove();
}

function addNewsItemField() {
    const wrapper = document.getElementById('news-item-fields');
    const firstField = wrapper.querySelector('.news-item-field');
    const newField = firstField.cloneNode(true);

    newField.querySelector('h4').textContent = 'News Slide ' + (newsItemIndex + 1);
    newField.querySelector('.remove-news-item').classList.remove('hidden');

    newField.querySelectorAll('input, textarea').forEach((field) => {
        field.name = field.name.replace(/news_items\[\d+\]/, 'news_items[' + newsItemIndex + ']');

        if (field.type === 'checkbox') {
            field.checked = true;
        } else if (field.type === 'number') {
            field.value = newsItemIndex;
        } else {
            field.value = '';
        }
    });

    wrapper.appendChild(newField);
    newsItemIndex++;
}

document.getElementById('add-news-item')?.addEventListener('click', addNewsItemField);
document.getElementById('news-item-fields')?.addEventListener('click', function (event) {
    const removeButton = event.target.closest('.remove-news-item');

    if (removeButton) {
        removeButton.closest('.news-item-field').remove();
    }
});

function deleteImage(fieldName) {
    event.preventDefault();
    const deleteField = document.getElementById('delete_' + fieldName);
    deleteField.value = '1';
    
    // Hide the image preview
    event.target.closest('.relative').style.display = 'none';
    
    // Show success message
    alert('Image will be deleted when you save');
}
</script>
