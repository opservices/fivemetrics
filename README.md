[FiveMetrics](https://fivemetrics.io)
================
[Website](https://fivemetrics.io) |
[Support Forum](https://fivemetrics.freshdesk.com)

FiveMetrics is an open source solution that provides a centralized view of your AWS environment with awesome dashboards, realtime and time series data collected from AWS specific APIs, including CloudWatch.

![](http://fivemetrics.io/imgs/docs/fivemetrics-overview.png)

## Installation using Docker
 Starting creating a FiveMetrics image container *(This operation can take a little time)*.
 ```bash
 docker build -t opservices/fivemetrics github.com/opservices/fivemetrics
```
With an image available, your can start your container binding the external port 5555.
```bash
 docker run --name fivemetrics -p 5555:80 -d opservices/fivemetrics
```

A default user '*tester@fivemetrics.io*' with password '*pass*' is provided, but you can create a new user using **'Register'** link at login time. 

If you have any problems please access our [Support Page](https://fivemetrics.freshdesk.com/).

## Documentation & Support
We provides a FAQ on  [Support Page](https://fivemetrics.freshdesk.com/). 

## Run from master
TODO


## Contribute

Feel free to submit questions and suggestions regarding this project. We'll love to hear about it. ❤️

If you think this project might be interesting for you or your friends, please don't hesitate in spread a word. 

## License

FiveMetrics is distributed under Apache 2.0 License.

