This folder contains Docker compose script and sample configurations for Rhasspy.

Full Rhasspy documentation is available [here](https://rhasspy.readthedocs.io/en/latest/). It is important that you go through the official documentation to setup Rhasspy properly and use it to it's full potential.

To run Rhasspy,

```
docker-compose up
```

The `profiles/en` folder contains two files:
1. `sentences.ini` - Contains the sentences to trigger this Rhasspy intent
2.`sample-profile.json` - Contains the sample profile that I currently use. Use this as an example, but it probably will not work for you without tweaks.

There is another file `asound.conf` that contains a custom [dsnoop](https://alsa.opensrc.org/Dsnoop) interface in order to allow the microphone to be used between two applications at the same time. This can be modified as per your hardware configuration and then copied to `/etc/asound.conf`.
