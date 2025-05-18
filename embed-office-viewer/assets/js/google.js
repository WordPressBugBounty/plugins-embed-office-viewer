window.onload = () => {
    let accessToken = null;
    let pickerInited = false;
    let gisInited = false;
    let tokenClient = null;

    const googlePicker = document.querySelector("#eov_google_picker");

    // Configuration
    const CLIENT_ID = api.google.client_id;
    const API_KEY = api.google.api_key;
    const APP_ID = api.google.project_number;
    const SCOPES = 'https://www.googleapis.com/auth/drive.metadata.readonly';

    // Initialize the component
    initGooglePicker();
    function initGooglePicker() {
        loadScripts();
    }

    function loadScripts() {
        const loadGapi = new Promise((resolve) => {
            const script = document.createElement('script');
            script.src = 'https://apis.google.com/js/api.js';
            script.onload = () => {
                window.gapi.load('client:picker', async () => {
                    try {
                        await window.gapi.client.load('https://www.googleapis.com/discovery/v1/apis/drive/v3/rest');
                        pickerInited = true;
                        maybeEnableButtons();
                    } catch (error) {
                        console.error('Error loading GAPI client', error);
                    }
                });
                resolve();
            };
            script.onerror = () => {
                console.error('Failed to load Google API script');
                resolve();
            };
            document.body.appendChild(script);
        });
        const loadGis = new Promise((resolve) => {
            const script = document.createElement('script');
            script.src = 'https://accounts.google.com/gsi/client';
            script.onload = () => {
                tokenClient = window.google.accounts.oauth2.initTokenClient({
                    client_id: CLIENT_ID,
                    scope: SCOPES,
                    callback: '', // Will be set later
                });
                gisInited = true;
                maybeEnableButtons();
                resolve();
            };
            script.onerror = () => {
                console.error('Failed to load Google Identity Services script');
                resolve();
            };
            document.body.appendChild(script);
        });
        Promise.all([loadGapi, loadGis]);
    }

    function maybeEnableButtons() {
        return pickerInited && gisInited;
    }

    googlePicker.addEventListener('click', function () {
        handleAuthClick();
    });

    function handleAuthClick() {
        tokenClient.callback = async (response) => {
            if (response.error) {
                throw response;
            }
            accessToken = response.access_token;
            sessionStorage.setItem('google_access_token', response.access_token);
            createPicker(response.access_token);
        };
        if (!accessToken) {
            tokenClient.requestAccessToken({ prompt: 'consent' });
        } else {
            tokenClient.requestAccessToken({ prompt: '' });
        }
    }

    function createPicker(token) {
        const view = new window.google.picker.View(window.google.picker.ViewId.DOCS);
        const picker = new window.google.picker.PickerBuilder()
            .enableFeature(window.google.picker.Feature.NAV_HIDDEN)
            .enableFeature(window.google.picker.Feature.MULTISELECT_ENABLED)
            .setDeveloperKey(API_KEY)
            .setAppId(APP_ID)
            .setOAuthToken(token)
            .addView(view)
            .addView(new window.google.picker.DocsUploadView())
            .setCallback(pickerCallback)
            .build();
        picker.setVisible(true);
    }

    function pickerCallback(data) {
        if (data.action === window.google.picker.Action.PICKED) {
            document.getElementById("eov_google_document_url").value = data.docs[0].embedUrl;
        }
    }
};
