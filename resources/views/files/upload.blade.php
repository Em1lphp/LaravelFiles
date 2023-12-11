<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{ route('file.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" id="fileInput" name="file">
    <button id="startUpload">Start upload</button>
</form>
<script>
    let isUploading = false;
    let uploadedChunks = [];
    let fileName =''
    const startButton = document.getElementById('startButton')

    function createFileChunks(file, chunkSize) {
        const chunks = [];
        let offset = 0;

        while (offset < file.size) {
            const chunk = file.slice(offset, offset + chunkSize);
            chunks.push(chunk);
            offset += chunkSize;
        }

        return chunks;
    }

    async function uploadChunk(chunk, uploadUrl) {
        const formData = new FormData();
        formData.append('fileChunk', chunk);

        try {
            await fetch(uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-File-Id': fileName
                }
            });

        } catch (error) {
            console.error(error);
        }
    }

    async function uploadFileInChunks(file, chunkSize, uploadUrl) {
        isUploading = true;
        const chunks = createFileChunks(file, chunkSize);

        for (let i = 0; i < chunks.length && isUploading; i++) {
            await uploadChunk(chunks[i], uploadUrl);
            uploadedChunks.push(chunks[i]);
        }
    }

    startButton.addEventListener('click', async () => {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        const chunkSize = 1024 * 1024;
        const uploadUrl = 'https://example.com/upload'; // URL для загрузки файла на сервер
        if(file){
            startButton.style.display="none"
            pauseButton.style.display="inline-block"
            cancelButton.style.display="inline-block"
            fileName = file.name+ '_'+ file.lastModified
        }
        await uploadFileInChunks(file, chunkSize, uploadUrl);
    });

    pauseButton.addEventListener('click', () => {
        isUploading = false;
        pauseButton.style.display = "none"
        resumeButton.style.display="inline-block"
    });

    resumeButton.addEventListener('click', async () => {

        const file = fileInput.files[0];
        const chunkSize = 1024 * 1024;
        const uploadUrl = 'https://example.com/upload'; // URL для загрузки файла на сервер
        resumeButton.style.display = "none"
        pauseButton.style.display="inline-block"
        await resumeUpload(file, chunkSize, uploadUrl);
    });
    cancelButton.addEventListener('click', () => {
        isUploading = false;
        uploadedChunks = [];
        fileName= ''
        startButton.style.display="inline-block"
        cancelButton.style.display="none"
        pauseButton.style.display="none"
        resumeButton.style.display = "none"
        fileInput.value =[]
    });

    async function resumeUpload(file, chunkSize, uploadUrl) {
        isUploading = true;
        const chunksToUpload = uploadedChunks.slice();

        while (chunksToUpload.length > 0 && isUploading) {
            const chunk = chunksToUpload.shift();
            try {
                await uploadChunk(chunk, uploadUrl);
            } catch (error) {
                isUploading = false;
                break;
            }
        }

        if (isUploading) {
            uploadedChunks = [];
        }
    }


</script>
</body>
</html>


