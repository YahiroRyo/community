import firebase from 'firebase'

const getUidAndToken = async() => ***REMOVED***
    return new Promise(async(resolve, reject) => ***REMOVED***
        let result = ***REMOVED***
            isError:    false,
            token:      null,
            uid:        null,
        ***REMOVED***
        await firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
            result.uid = user.uid
            if (user) ***REMOVED***
                await user.getIdToken().then((token) => ***REMOVED***
                    result.token = token
                ***REMOVED***)
                .catch(() => ***REMOVED***
                    result.isError = true
                    reject(result)
                ***REMOVED***)
            ***REMOVED*** else ***REMOVED***
                result.isError = true
                reject(result)
            ***REMOVED***
            resolve(result)
        ***REMOVED***)
    ***REMOVED***)
***REMOVED***

export ***REMOVED*** getUidAndToken ***REMOVED*** 