
https://gist.github.com/simongregory/2c60d270006d4bf727babca53dca1f87
function waitForEventWithTimeout(emitter, eventName, timeout) {
    return new Promise((resolve, reject) => {
        let timer;

        function listener(data) {
            clearTimeout(timer);
            emitter.removeEventListener(eventName, listener);
            resolve(data);
        }

        emitter.addEventListener(eventName, listener);
        timer = setTimeout(() => {
            emitter.removeEventListener(eventName, listener);
            reject(new Error("timeout waiting for " + eventName));
        }, timeout);
    });
}

// Example usage
//const promise = waitForEventWithTimeout(session, 'message', 2000);