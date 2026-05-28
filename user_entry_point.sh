#!/bin/sh

set -e

USER_ID=${1}
GROUP_ID=${2}
USER_NAME="appuser"
GROUP_NAME="appgroup"

if getent passwd ${USER_ID} >/dev/null; then
    echo "User ID ${USER_ID} already exists. Skipping creation."
else
    groupadd -g ${GROUP_ID} ${GROUP_NAME}
    useradd -u ${USER_ID} -m -g ${GROUP_ID} ${USER_NAME}

    echo "User ID ${USER_ID} does not exist. User has been created."
fi
