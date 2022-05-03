FROM alpine:3.13.3
LABEL maintainer="Will<development@hexbatch.com>" \
      description="Docker image for Doxygen documentation generator."

RUN echo http://dl-cdn.alpinelinux.org/alpine/edge/main/ >> /etc/apk/repositories
RUN echo http://dl-cdn.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN echo http://dl-cdn.alpinelinux.org/alpine/edge/testing/ >> /etc/apk/repositories

ENV DOXYGEN_VERSION=1.9.3-r1

RUN apk add --no-cache doxygen=${DOXYGEN_VERSION} graphviz fontconfig font-noto font-noto-emoji font-noto-cjk


WORKDIR /data
ENTRYPOINT ["doxygen"]