export class ImagePreview{
    constructor(listenersSelector, previewSelector){
        this.listeners = document.querySelectorAll(listenersSelector);
        this.previewer = document.querySelector(previewSelector);

        for(const listener of this.listeners){
            listener.addEventListener('change', e => this.preview(e));
        }
    }

    preview(event){
        const input = event.target;
        this.previewer.classList.remove('hide');

        if(input.getAttribute('type') === 'file'){
            if(!input.files[0]){
                this.hidePreviewer();
                return;
            }

            const regex = new RegExp("(.*?)\.(png|jpg|jpeg)$");
            if(!regex.test(input.value.toLowerCase())){
                this.hidePreviewer();
                return;
            }

            const data = URL.createObjectURL(input.files[0]);
            this.setPreviewer(data);
        }else{
            const imageChecker = new Image();
            imageChecker.src = input.value;
            imageChecker.onerror = () => {
                this.hidePreviewer();
            };
            imageChecker.onload = () => {
                this.setPreviewer(input.value);
            };
        }
    }

    setPreviewer(url){
        this.previewer.setAttribute('src', url);
    }

    hidePreviewer(){
        this.previewer.classList.add('hide');
    }
}