FROM ubuntu:latest
LABEL authors="mf384"

ENTRYPOINT ["top", "-b"]