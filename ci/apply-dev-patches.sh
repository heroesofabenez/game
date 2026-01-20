#!/bin/bash
patch -p1 --forward <patches/kdyby_fake-session-compatibility.patch || true
