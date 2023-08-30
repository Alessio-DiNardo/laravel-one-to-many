<div class="container" id="posts-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @error('title')
                        <label for="exampleFormControlInput1" class="form-label">
                            Title
                        </label>
                        <input type="text" class="form-control" id="title" placeholder="Insert your post's title" name="title"  value="{{ old('title', '') }}">
        </div>

        @error('image')

            <label for="image" class="form-label">
                Image
            </label>
            <input type="file" name="image" id="image" class="form-control" placeholder="Upload your image" value="{{ old('image', '') }}">
    </div>

        @error('content')

            Content
            </label>
            <textarea class="form-control" id="content" rows="7" name="content">
                {{ old('content', '') }}
            </textarea>
</div>